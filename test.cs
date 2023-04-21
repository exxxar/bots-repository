async Task ReceiveMessageAsync()
{
    // сокет для прослушки сообщений
    using UdpClient receiver = new UdpClient(localPort);
    while (true)
    {
        // получаем данные в массив data
        var result = await receiver.ReceiveAsync();
        var message = Encoding.UTF8.GetString(result.Buffer);
        // выводим сообщение
        Print(message);
    }
}

void Print(string message)
{
    if (OperatingSystem.IsWindows())    // если ОС Windows
    {
        var position = Console.GetCursorPosition(); // получаем текущую позицию курсора
        int left = position.Left;   // смещение в символах относительно левого края
        int top = position.Top;     // смещение в строках относительно верха
        // копируем ранее введенные символы в строке на следующую строку
        Console.MoveBufferArea(0, top, left, 1, 0, top + 1);
        // устанавливаем курсор в начало текущей строки
        Console.SetCursorPosition(0, top);
        // в текущей строке выводит полученное сообщение
        Console.WriteLine(message);
        // переносим курсор на следующую строку
        // и пользователь продолжает ввод уже на следующей строке
        Console.SetCursorPosition(left, top + 1);
    }
    else Console.WriteLine(message);
}

