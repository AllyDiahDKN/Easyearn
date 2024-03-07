<?php
function generateMetaTags($pageTitle) {
    // Default meta tags
    $author = "Sia Square Company Limited (Ikram Mohamed)";

    // Meta tags specific to each page
    switch ($pageTitle) {
        case 'home':
            $title = "Earn Money online in Tanzania - Easy Earn Tanzania";
            $keywords = "earn money, online, Tanzania, Easy Earn Tanzania";
            $description = "Earn money online in Tanzania with Easy Earn Tanzania. Join now and start earning!";
            break;
        case 'shop':
            $title = "Shop - Easy Earn Tanzania";
            $keywords = "shop, products, online store, buy, Easy Earn Tanzania";
            $description = "Explore a wide range of products at Easy Earn Tanzania's online shop. Shop now!";
            break;
        case 'about':
            $title = "About Us - Easy Earn Tanzania";
            $keywords = "about us, company, information, Easy Earn Tanzania";
            $description = "Learn more about Easy Earn Tanzania - your trusted online earning platform.";
            break;
        // Add more cases for other pages as needed
        default:
            $title = "Easy Earn Tanzania";
            $keywords = "earn money, online, Tanzania, Easy Earn Tanzania";
            $description = "Earn money online in Tanzania with Easy Earn Tanzania. Join now and start earning!";
            break;
    }

    // Output meta tags
    echo ' <meta charset="UTF-8">';
    echo '<meta http-equiv="x-ua-compatible" content="ie=edge" />';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">';
    echo '<title>' . $title . '</title>' . PHP_EOL;
    echo '<meta name="keywords" content="' . $keywords . '" />' . PHP_EOL;
    echo '<meta name="description" content="' . $description . '" />' . PHP_EOL;
    echo '<meta name="author" content="' . $author . '" />' . PHP_EOL;
    echo '<link rel="icon" href="assets/images/favicon/favicon.png" sizes="32x32" />' . PHP_EOL;
    echo '<link rel="apple-touch-icon" href="assets/images/favicon/favicon.png" />' . PHP_EOL;
    echo '<meta name="msapplication-TileImage" content="assets/images/favicon/favicon.png" />' . PHP_EOL;
}
?>
