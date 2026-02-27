<?php
echo "PDO Drivers: ";
print_r(PDO::getAvailableDrivers());
echo "\n";

if (!extension_loaded('pdo_mysql')) {
    echo "PDO MySQL extension is NOT loaded\n";
} else {
    echo "PDO MySQL extension is loaded\n";
}
?>
