# DefaultApi

All URIs are relative to *http://localhost:8000/api*

Method | HTTP request | Description
------------- | ------------- | -------------
[**categoriesGet**](DefaultApi.md#categoriesGet) | **GET** /categories | Получить все категории
[**categoriesIdDelete**](DefaultApi.md#categoriesIdDelete) | **DELETE** /categories/{id} | Удалить категорию по ID
[**categoriesIdGet**](DefaultApi.md#categoriesIdGet) | **GET** /categories/{id} | Получить категорию по ID
[**categoriesIdPut**](DefaultApi.md#categoriesIdPut) | **PUT** /categories/{id} | Обновить информацию о категории
[**categoriesPost**](DefaultApi.md#categoriesPost) | **POST** /categories | Добавить новую категорию
[**housesGet**](DefaultApi.md#housesGet) | **GET** /houses | Получить все дома
[**housesIdDelete**](DefaultApi.md#housesIdDelete) | **DELETE** /houses/{id} | Удалить дом по ID
[**housesIdGet**](DefaultApi.md#housesIdGet) | **GET** /houses/{id} | Получить дом по ID
[**housesIdPut**](DefaultApi.md#housesIdPut) | **PUT** /houses/{id} | Обновить информацию о доме
[**housesPost**](DefaultApi.md#housesPost) | **POST** /houses | Добавить новый дом
[**paymentsGet**](DefaultApi.md#paymentsGet) | **GET** /payments | Получить все платежи
[**paymentsPost**](DefaultApi.md#paymentsPost) | **POST** /payments | Добавить новый платеж
[**tenantsGet**](DefaultApi.md#tenantsGet) | **GET** /tenants | Получить всех арендаторов
[**tenantsPost**](DefaultApi.md#tenantsPost) | **POST** /tenants | Добавить нового арендатора
[**usersGet**](DefaultApi.md#usersGet) | **GET** /users | Получить всех пользователей
[**usersPost**](DefaultApi.md#usersPost) | **POST** /users | Добавить нового пользователя


<a name="categoriesGet"></a>
# **categoriesGet**
> List&lt;InlineResponse200&gt; categoriesGet()

Получить все категории

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    try {
      List<InlineResponse200> result = apiInstance.categoriesGet();
      System.out.println(result);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#categoriesGet");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**List&lt;InlineResponse200&gt;**](InlineResponse200.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | Список категорий |  -  |

<a name="categoriesIdDelete"></a>
# **categoriesIdDelete**
> categoriesIdDelete(id)

Удалить категорию по ID

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    Integer id = 56; // Integer | Идентификатор категории
    try {
      apiInstance.categoriesIdDelete(id);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#categoriesIdDelete");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **Integer**| Идентификатор категории |

### Return type

null (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | Категория удалена |  -  |
**404** | Категория не найдена |  -  |

<a name="categoriesIdGet"></a>
# **categoriesIdGet**
> InlineResponse200 categoriesIdGet(id)

Получить категорию по ID

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    Integer id = 56; // Integer | Идентификатор категории
    try {
      InlineResponse200 result = apiInstance.categoriesIdGet(id);
      System.out.println(result);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#categoriesIdGet");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **Integer**| Идентификатор категории |

### Return type

[**InlineResponse200**](InlineResponse200.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | Информация о категории |  -  |
**404** | Категория не найдена |  -  |

<a name="categoriesIdPut"></a>
# **categoriesIdPut**
> categoriesIdPut(id, inlineObject1)

Обновить информацию о категории

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    Integer id = 56; // Integer | Идентификатор категории
    InlineObject1 inlineObject1 = new InlineObject1(); // InlineObject1 | 
    try {
      apiInstance.categoriesIdPut(id, inlineObject1);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#categoriesIdPut");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **Integer**| Идентификатор категории |
 **inlineObject1** | [**InlineObject1**](InlineObject1.md)|  |

### Return type

null (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: Not defined

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | Категория обновлена |  -  |
**404** | Категория не найдена |  -  |

<a name="categoriesPost"></a>
# **categoriesPost**
> categoriesPost(inlineObject)

Добавить новую категорию

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    InlineObject inlineObject = new InlineObject(); // InlineObject | 
    try {
      apiInstance.categoriesPost(inlineObject);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#categoriesPost");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **inlineObject** | [**InlineObject**](InlineObject.md)|  |

### Return type

null (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: Not defined

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**201** | Категория создана |  -  |

<a name="housesGet"></a>
# **housesGet**
> List&lt;InlineResponse2001&gt; housesGet()

Получить все дома

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    try {
      List<InlineResponse2001> result = apiInstance.housesGet();
      System.out.println(result);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#housesGet");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**List&lt;InlineResponse2001&gt;**](InlineResponse2001.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | Список домов |  -  |

<a name="housesIdDelete"></a>
# **housesIdDelete**
> housesIdDelete(id)

Удалить дом по ID

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    Integer id = 56; // Integer | Идентификатор дома
    try {
      apiInstance.housesIdDelete(id);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#housesIdDelete");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **Integer**| Идентификатор дома |

### Return type

null (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | Дом удален |  -  |
**404** | Дом не найден |  -  |

<a name="housesIdGet"></a>
# **housesIdGet**
> InlineResponse2001 housesIdGet(id)

Получить дом по ID

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    Integer id = 56; // Integer | Идентификатор дома
    try {
      InlineResponse2001 result = apiInstance.housesIdGet(id);
      System.out.println(result);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#housesIdGet");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **Integer**| Идентификатор дома |

### Return type

[**InlineResponse2001**](InlineResponse2001.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | Информация о доме |  -  |
**404** | Дом не найден |  -  |

<a name="housesIdPut"></a>
# **housesIdPut**
> housesIdPut(id, inlineObject3)

Обновить информацию о доме

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    Integer id = 56; // Integer | Идентификатор дома
    InlineObject3 inlineObject3 = new InlineObject3(); // InlineObject3 | 
    try {
      apiInstance.housesIdPut(id, inlineObject3);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#housesIdPut");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **Integer**| Идентификатор дома |
 **inlineObject3** | [**InlineObject3**](InlineObject3.md)|  |

### Return type

null (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: Not defined

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | Дом обновлен |  -  |
**404** | Дом не найден |  -  |

<a name="housesPost"></a>
# **housesPost**
> housesPost(inlineObject2)

Добавить новый дом

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    InlineObject2 inlineObject2 = new InlineObject2(); // InlineObject2 | 
    try {
      apiInstance.housesPost(inlineObject2);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#housesPost");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **inlineObject2** | [**InlineObject2**](InlineObject2.md)|  |

### Return type

null (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: Not defined

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**201** | Дом добавлен |  -  |

<a name="paymentsGet"></a>
# **paymentsGet**
> List&lt;InlineResponse2002&gt; paymentsGet()

Получить все платежи

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    try {
      List<InlineResponse2002> result = apiInstance.paymentsGet();
      System.out.println(result);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#paymentsGet");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**List&lt;InlineResponse2002&gt;**](InlineResponse2002.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | Список платежей |  -  |

<a name="paymentsPost"></a>
# **paymentsPost**
> paymentsPost(inlineObject4)

Добавить новый платеж

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    InlineObject4 inlineObject4 = new InlineObject4(); // InlineObject4 | 
    try {
      apiInstance.paymentsPost(inlineObject4);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#paymentsPost");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **inlineObject4** | [**InlineObject4**](InlineObject4.md)|  |

### Return type

null (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: Not defined

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**201** | Платеж добавлен |  -  |

<a name="tenantsGet"></a>
# **tenantsGet**
> List&lt;InlineResponse2003&gt; tenantsGet()

Получить всех арендаторов

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    try {
      List<InlineResponse2003> result = apiInstance.tenantsGet();
      System.out.println(result);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#tenantsGet");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**List&lt;InlineResponse2003&gt;**](InlineResponse2003.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | Список арендаторов |  -  |

<a name="tenantsPost"></a>
# **tenantsPost**
> tenantsPost(inlineObject5)

Добавить нового арендатора

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    InlineObject5 inlineObject5 = new InlineObject5(); // InlineObject5 | 
    try {
      apiInstance.tenantsPost(inlineObject5);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#tenantsPost");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **inlineObject5** | [**InlineObject5**](InlineObject5.md)|  |

### Return type

null (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: Not defined

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**201** | Арендатор добавлен |  -  |

<a name="usersGet"></a>
# **usersGet**
> List&lt;InlineResponse2004&gt; usersGet()

Получить всех пользователей

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    try {
      List<InlineResponse2004> result = apiInstance.usersGet();
      System.out.println(result);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#usersGet");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**List&lt;InlineResponse2004&gt;**](InlineResponse2004.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | Список пользователей |  -  |

<a name="usersPost"></a>
# **usersPost**
> usersPost(inlineObject6)

Добавить нового пользователя

### Example
```java
// Import classes:
import org.openapitools.client.ApiClient;
import org.openapitools.client.ApiException;
import org.openapitools.client.Configuration;
import org.openapitools.client.models.*;
import org.openapitools.client.api.DefaultApi;

public class Example {
  public static void main(String[] args) {
    ApiClient defaultClient = Configuration.getDefaultApiClient();
    defaultClient.setBasePath("http://localhost:8000/api");

    DefaultApi apiInstance = new DefaultApi(defaultClient);
    InlineObject6 inlineObject6 = new InlineObject6(); // InlineObject6 | 
    try {
      apiInstance.usersPost(inlineObject6);
    } catch (ApiException e) {
      System.err.println("Exception when calling DefaultApi#usersPost");
      System.err.println("Status code: " + e.getCode());
      System.err.println("Reason: " + e.getResponseBody());
      System.err.println("Response headers: " + e.getResponseHeaders());
      e.printStackTrace();
    }
  }
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **inlineObject6** | [**InlineObject6**](InlineObject6.md)|  |

### Return type

null (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: Not defined

### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
**201** | Пользователь добавлен |  -  |

