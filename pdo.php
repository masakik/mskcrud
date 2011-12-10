<?php class MyPDO extends PDO
{
    public function __construct($file = 'pdo.ini')
    {
        if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Unable to open ' . $file . '.');

        $dns = $settings['database']['driver'] .
        ':host=' . $settings['database']['host'] .
        ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '') .
        ';dbname=' . $settings['database']['db_name'];
echo $dns;
        $d = PDO::getAvailableDrivers();
        print_r($d);
        parent::__construct($dns, $settings['database']['username'], $settings['database']['password']);
    }
}

?>

<?php  // examples
$res = new MyPDO;
$stmt = Database :: prepare("SELECT 'something' ;");
$stmt->execute();
var_dump($stmt->fetchAll());
$stmt->closeCursor();
?>