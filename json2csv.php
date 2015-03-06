<?php

// show help
function help() {
  echo "Usage: json2csv.php [options] <file>\n";
  echo "\n";
  echo "  -h, --help show this help message and exit.\n";
  echo "  -r, --reverse csv file convert to json file.\n";
  exit;
}

// convert json file to csv file
function json2csv($filepath) {
  $obj = json_decode(file_get_contents($filepath), true);
  $file = new SplFileObject(preg_replace('/^(.*).json$/i', '$1.csv', $filepath), 'w');

  // output header text
  if (array_values($obj) !== $obj) {
    $obj = array($obj);
  }
  $key_names = array_keys($obj[0]);
  $file->fputcsv($key_names);

  // output csv
  foreach ($obj as $line) {
    // check same object
    if (array_values($key_names) !== array_values(array_keys($line))) continue;

    // serialize array and object
    foreach ($line as &$value) {
      if (!is_array($value)) continue;

      $value = serialize($value);
    }

    $file->fputcsv($line);
  }

  $file = null;
}

// convert csv file to json file
function csv2json($filepath) {
  $file = new SplFileObject($filepath);
  $file->setFlags(SplFileObject::READ_CSV);

  // reading csv file
  $obj = array();
  $key_names = array();
  foreach ($file as $number => $line) {
    // EOF measures
    if (empty($line[0])) continue;

    // create object keys from csv header
    if ($number == 0) {
      $key_names = $line;
      continue;
    }

    // create object
    $tmp = array();
    foreach ($key_names as $key => $value) {
      // serialized value => unserialized value
      // non serialized value => same value
      // empty array => array(), empty string => null
      $tmp[$value] = ($unserialized = @unserialize($line[$key])) !== false ? $unserialized : (!empty($line[$key]) ? $line[$key] : (is_array($line[$key]) ? array() : null));
    }
    if (!empty($tmp)) {
      $obj[] = $tmp;
    }
  }

  // output json
  file_put_contents(preg_replace('/^(.*).csv$/i', '$1.json', $filepath), json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
}

// main
function main($c, $v) {
  $func = 'json2csv';
  $filepath = null;

  for ($i = 1; $i < count($v); $i++) {
    switch ($v[$i]) {
      case '-h':
      case '--help':
        help();
        break;
      case '-r':
      case '--reverse':
        $func = 'csv2json';
        break;
      default:
        if (!file_exists($v[$i]) || !empty($filepath)) {
          help();
        }
        $filepath = $v[$i];
        break;
    }
  }

  if (empty($filepath)) {
    help();
  }

  $func($filepath);
}

main($argc, $argv);
