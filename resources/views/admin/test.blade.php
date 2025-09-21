<!DOCTYPE html>
<html>
<head>
    <title>Admin Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold text-green-600 mb-4">✅ Admin Test Working!</h1>
        <p class="text-gray-700">{{ $message }}</p>
        <p class="text-gray-500 mt-4">Timestamp: {{ now() }}</p>
        <a href="/admin/login" class="inline-block mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Go to Admin Login
        </a>
    </div>
</body>
</html>
