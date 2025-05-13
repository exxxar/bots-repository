<?php

namespace App\Http\BusinessLogic;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BitrixService
{
    protected $webhookUrl;

    public function __construct($url)
    {
        $this->webhookUrl = $url;
    }

    // Добавление товара
    public function addProduct(array $productData)
    {
        return $this->request('crm.product.add', ['fields' => $productData]);
    }

    public function getLeadProducts($leadId)
    {
        return $this->request("crm.lead.productrows.get", [
            'ID' => $leadId,
        ]);
    }

    public function getFolder($folderId)
    {
        return $this->request('disk.folder.get', [
            'id' => $folderId
        ]);
    }

    public function listFolders()
    {
        return $this->request('disk.storage.getlist', [

        ]);
    }

    public function uploadDocument($folderId, $filePath, $fileName)
    {

        return $this->request('disk.folder.uploadfile', [
            'id' => $folderId, // ID папки в "Диске"
            'fileContent' => $filePath,
            'data' => [
                'NAME' => $fileName
            ],
            'generateUniqueName' => 'Y'
        ]);
    }

    // Добавление товара к лиду
    public function addProductToDeal($dealId, array $productRows)
    {
        return $this->request("crm.deal.productrows.set", [
            'id' => $dealId,
            'rows' => $productRows
        ]);
    }

    // Добавление товара к лиду
    public function addProductToLead($leadId, array $productRows)
    {
        return $this->request("crm.lead.productrows.set", [
            'id' => $leadId,
            'rows' => $productRows
        ]);
    }

    public function getLeadDealFields()
    {
        return $this->request("crm.deal.userfield.list", [
            "select"=>  ["ID", "FIELD_NAME", "USER_TYPE_ID", "EDIT_FORM_LABEL", "LIST_COLUMN_LABEL","LIST_FILTER_LABEL"]
        ]);
    }



    public function getStatusList()
    {
        return $this->request("crm.status.list", [

        ]);
    }

    public function getLeadFields()
    {
        return $this->request("crm.lead.fields", [
            "select"=>["ID", "FIELD_NAME", "USER_TYPE_ID", "EDIT_FORM_LABEL"]
        ]);
    }

    // Добавление документа к лиду
    public function addDocumentToDeal($dealId, $fName, $fPath, $param = null)
    {


        return $this->request('crm.deal.update', [
            'id' => $dealId,
            'fields' => [
                "$param" => [
                    "fileData" => [$fName, $fPath]
                ]
            ]
        ]);

    }

    // Добавление документа к лиду
    public function addDocumentToLead($leadId, $fName, $fPath, $param = null)
    {


        return $this->request('crm.lead.update', [
            'id' => $leadId,
            'fields' => [
                "$param" => [
                    "fileData" => [$fName, $fPath]
                ]
            ]
        ]);

    }

    // Добавление документа к лиду
    public function addDocumentsToDeal($dealId, array $files, $param = null)
    {

        //   $param = is_null($param) ? env('DOCUMENT_FILED_CODE_SPECIFICATION') : null;

        $documents = [];

        foreach ($files as $file) {
            $documents[] = [
                "fileData" => [$file["name"], $file["path"]]
            ];
        }

        return $this->request('crm.deal.update', [
            'id' => $dealId,
            'fields' => [
                "$param" => $documents // UF_CRM_XXXXXX - поле "Файл"
            ]
        ]);

    }

    // Добавление документа к лиду
    public function addDocumentsToLead($leadId, array $files, $param = null)
    {

        //   $param = is_null($param) ? env('DOCUMENT_FILED_CODE_SPECIFICATION') : null;

        $documents = [];

        foreach ($files as $file) {
            $documents[] = [
                "fileData" => [$file["name"], $file["path"]]
            ];
        }

        return $this->request('crm.lead.update', [
            'id' => $leadId,
            'fields' => [
                "$param" => $documents // UF_CRM_XXXXXX - поле "Файл"
            ]
        ]);

    }

    // Создание лида
    public function getLead(int $id)
    {
        return $this->request('crm.lead.get', ['id' => $id]);
    }


    // Создание лида
    public function createLead(array $leadData)
    {
        return $this->request('crm.lead.add', ['fields' => $leadData]);
    }

    // Создание лида
    public function createDeal(array $leadData)
    {
        return $this->request('crm.deal.add', ['fields' => $leadData]);
    }


    // Удаление товара
    public function deleteProduct($productId)
    {
        return $this->request('crm.product.delete', ['id' => $productId]);
    }

    // Удаление документа у лида
    public function deleteDocumentFromLead($leadId)
    {
        // Получаем лид
        $lead = $this->request('crm.lead.get', ['id' => $leadId]);

        if (!isset($lead['result']['UF_CRM_12345'])) { // UF_CRM_12345 - поле с файлом
            return ['error' => 'Документ не найден у лида'];
        }

        $fileId = $lead['result']['UF_CRM_12345']; // ID файла
        return $this->request('disk.file.delete', ['id' => $fileId]);
    }

    // Получение списка лидов
    public function getLeads(array $filter = [], array $select = ['ID', 'TITLE', 'PHONE', 'STATUS_ID'])
    {
        $select[] = env("DOCUMENT_FILED_CODE");
        return $this->request('crm.lead.list', [
            'order' => ['ID' => 'DESC'],
            'filter' => $filter,
            'select' => $select
        ]);
    }

    // Получение списка товаров
    public function getProducts(array $filter = [], array $select = ['ID', 'NAME', 'PRICE'])
    {
        return $this->request('crm.product.list', [
            'order' => ['ID' => 'DESC'],
            'filter' => $filter,
            'select' => $select
        ]);
    }

    // Обновление документа в лиде
    public function updateDocumentInLead($leadId, string $newFilePath)
    {
        $this->deleteDocumentFromLead($leadId);
        return $this->addDocumentToLead($leadId, $newFilePath);
    }

    // Редактирование лида
    public function updateLead($leadId, array $updateData)
    {
        return $this->request('crm.lead.update', [
            'id' => $leadId,
            'fields' => $updateData
        ]);
    }

    public function updateDeal($dealId, array $updateData)
    {
        return $this->request('crm.deal.update', [
            'id' => $dealId,
            'fields' => $updateData
        ]);
    }


    // Поиск контакта по номеру телефона
    public function findContactByPhone($phone)
    {
        return $this->request('crm.contact.list', [
            'filter' => ['PHONE' => $phone],
            'select' => ['ID']
        ]);
    }

    // Создание нового контакта
    public function createContact(array $contactData)
    {
        return $this->request('crm.contact.add', [
            'fields' => $contactData
        ]);
    }

    // Обновление существующего контакта
    public function updateContact($contactId, array $updateData)
    {
        return $this->request('crm.contact.update', [
            'id' => $contactId,
            'fields' => $updateData
        ]);
    }

    // Создание или обновление контакта по номеру телефона
    public function upsertContact(array $contactData)
    {
        $phone = $contactData['PHONE'][0]['VALUE'] ?? null;

        if (!$phone) {
            return ['error' => 'Phone number is required'];
        }

        $existingContact = $this->findContactByPhone($phone);

        if (!empty($existingContact['result'])) {
            $contactId = $existingContact['result'][0]['ID'];
            return $this->updateContact($contactId, $contactData);
        }

        return $this->createContact($contactData);
    }

    // Вспомогательный метод для запросов к API
    protected function request($method, $params = [])
    {
        if (is_null($this->webhookUrl) || is_null($method))
            return [];

        try {
            $response = Http::post("{$this->webhookUrl}{$method}.json", $params);

            return $response->json();
        }catch (\Exception $e){
            Log::info($e);
        }

    }
}
