<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Fetch products from the database
$query = "SELECT id, name, description, price, image_url FROM products"; // Adjust table name as needed
$result = $db->query($query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kween P Sports</title>
    <!-- tailwind css cdn -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="images/headlogo.png"  type="image/x-icon">
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Red+Hat+Display:wght@500;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Zen+Dots&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="bg-black shadow-md top-0 left-0 w-full z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-2">
                <div class="flex-1 flex justify-start">
                    <div class="hidden md:flex space-x-4 p-2">
                        <a href="index.php" class="text-white tracking-wider px-4 xl:px-8 py-2 text-lg hover:underline">Home</a>
                        <a href="#varieties" class="text-white tracking-wider px-4 xl:px-8 py-2 text-lg hover:underline">Sports</a>
                        <a href="#about" class="text-white tracking-wider px-4 xl:px-8 py-2 text-lg hover:underline">About</a>
                    </div>
                </div>
                <div class="flex-1 flex justify-center">
                    <div class="text-center">
                        <img src="logo1.png" alt="Ricehub Logo" width="200px" class="h-20">  
                    </div>
                </div>
                <div class="flex-1 flex justify-end">
                    <div class="hidden md:flex space-x-4 p-2">
                        <a href="#threats" class="text-white tracking-wider px-4 xl:px-8 py-2 text-lg hover:underline">Services</a>
                        <a href="contacts.html" class="text-white tracking-wider px-4 xl:px-8 py-2 text-lg hover:underline">Contacts</a>
                        <a href="index.php" class="border-2 border-white bg-black text-white py-2 px-4 rounded">Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div id="navbar-menu" class="navbar-menu md:hidden">
                        <a href="#Main" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Home</a>
                        <a href="#varieties" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Sports</a>
                        <a href="#about" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">About</a>
                        <a href="contacts.html" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Contacts</a>
                      
                    </div>
    </nav>
    <!-- navbar script -->
    <script>
        document.getElementById('navbar-toggle').addEventListener('click', function() {
            var menu = document.getElementById('navbar-menu');
            menu.style.display = menu.style.display === 'none' || menu.style.display === '' ? 'block' : 'none';
        });
    </script>

    <!-- Your shop content here -->
     <h1 class="text-2xl text-center mt-5 mb-5 font-bold text-orange-700">READYMADE JERSEY'</h1>
    <div class="flex flex-wrap justify-start">
    <?php while ($product = $result->fetch_assoc()): ?>
        <div class="w-full md:w-1/2 lg:w-1/6 p-4">
            <div class="border p-4 bg-white rounded-lg shadow-lg">
            <h5 class="w-full h-10 object-cover rounded-t-lg font-bold text-center uppercase"><?php echo htmlspecialchars($product['name']); ?></h5>
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image" class="w-full h-48 object-cover rounded-t-lg">
                
                <p class="text-sm font-bold text-center text-gray-700"><?php echo htmlspecialchars($product['description']); ?></p>
                <p class="text-xl font-bold text-center text-gray-700">$<?php echo htmlspecialchars($product['price']); ?></p>
                <div class="flex justify-center mt-2">
                    <a href="orderForm.php?product_id=<?php echo $product['id']; ?>">
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-lg">BUY NOW</button>
                    </a>

                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
