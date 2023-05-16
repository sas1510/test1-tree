<?php
require_once 'node.php';
require_once 'enum.php';
global $comparisonCount;
global $BTcomparisonCount;
$Tree = new BinaryTree();
$comparisonCount = 0;
$BTcomparisonCount = 0;
$compcount = 0;
$input_name = 'file';
$allow = array('json');
$deny = array(
    'phtml', 'php', 'php3', 'php4', 'php5', 'php6', 'php7', 'phps', 'cgi', 'pl', 'asp',
    'aspx', 'shtml', 'shtm', 'htaccess', 'htpasswd', 'ini', 'log', 'sh', 'js', 'html',
    'htm', 'css', 'sql', 'spl', 'scgi', 'fcgi', 'exe'
);
$path = __DIR__ . '/uploads/';
$baseName = 'data.json';
$error = $success = $json = $compcount = '';
$dataFileName = $path.$baseName;
$request = [];
$searchResultFieldsList = [];
$content =[];

$data = [];


if (isset($_POST['json'])) {
    $requestData = json_decode($_POST['json'], true);
    if (isset($requestData['value']) && isset($requestData['field']) && isset($requestData['type'])) {
        if ($requestData['type'] === 'regular') {
            $success = 'Поиск перебором значения ';
            if (file_exists($path . $baseName)) {
                $content = json_decode(file_get_contents($dataFileName), true);
                foreach (search($content, $requestData['field'], $requestData['value']) as $key => $value) {
                    $request[intval($value)] = $content[intval($value)];
                }
                $compcount = $GLOBALS['comparisonCount'];
                $error = '';
            } else {
                $error = 'Загрузите сначала фаил';
            }
        } else if ($requestData['type'] === 'index') {
            $success = 'Индексный поиск ';
            if (file_exists($path . $baseName)) {
                $content = json_decode(file_get_contents($dataFileName), true);
                buildBinaryTree($Tree, $content, $requestData['field']);
                $Tree->saveToFile($_SERVER['DOCUMENT_ROOT'] . '/tree/index/' . $requestData['field'] . '.index.obj');
                $success .= '</br>Создан идексный фаил с именем '.$requestData['field'].'.index.obj <a href="/tree/index/' . $requestData['field'] . '.index.obj " target="_blanc"> <ЗАГРУЗИТЬ> </a>';
                foreach ($Tree->find($requestData['value']) as $key => $value) {
                    $request[intval($value)] = $content[intval($value)];
                }
                $compcount = $GLOBALS['BTcomparisonCount'];
                $error = '';

            } else {
                $error = 'Загрузите сначала фаил';
            }
        } else if ($requestData['type'] === 'all') {
            $success = 'Показать все документы';
            if (file_exists($path . $baseName)) {
                $request = json_decode(file_get_contents($dataFileName), true);
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/tree/log/content.arr', print_r($content, 1));
                $searchResultFieldsList = getFieldsList($content);
                $compcount = 0;
                $error = '';
            } else {
                $error = 'Загрузите сначала фаил';
            }
        } else {
            $error = 'Неверный запрос';
        }
    }

} else if (isset($_FILES[$input_name])) {
    $file = $_FILES[$input_name];

    // Проверим на ошибки загрузки.
    if (!empty($file['error']) || empty($file['tmp_name'])) {
        $error = 'Не удалось загрузить файл.';
    } elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
        $error = 'Не удалось загрузить файл.';
    } else {
        // Оставляем в имени файла только буквы, цифры и некоторые символы.
        $pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
        $name = mb_eregi_replace($pattern, '-', $file['name']);
        $name = mb_ereg_replace('[-]+', '-', $name);
        $parts = pathinfo($name);

        if (empty($name) || empty($parts['extension'])) {
            $error = 'Недопустимый тип файла';
        } elseif (!empty($allow) && !in_array(strtolower($parts['extension']), $allow)) {
            $error = 'Недопустимый тип файла';
        } elseif (!empty($deny) && in_array(strtolower($parts['extension']), $deny)) {
            $error = 'Недопустимый тип файла';
        } else {
            // Перемещаем файл в директорию.
            if (move_uploaded_file($file['tmp_name'], $path . $baseName)) {
                $dataFileName =  $path . $baseName;
                $success = 'Файл «' . $name . '» успешно загружен и сохранен с именем '.$dataFileName.'</br>';
                $content = json_decode(file_get_contents($dataFileName),true);
                file_put_contents($_SERVER['DOCUMENT_ROOT'].'/tree/log/content.arr', print_r($content,1));
                $searchResultFieldsList = getFieldsList($content);
                file_put_contents($_SERVER['DOCUMENT_ROOT'].'/tree/log/fields.arr', print_r($searchResultFieldsList,1));
                $request = $content;
                file_put_contents($_SERVER['DOCUMENT_ROOT'].'/tree/log/request.arr', print_r($request,1));
            } else {
                $error = 'Не удалось загрузить файл.';
            }
        }
    }
} else {
    $error = 'Некоректный запрос.';
}

// Вывод сообщения о результате загрузки.
if (!empty($error)) {
    $error = '<p style="color: red">' . $error . '</p>';
}

$data['error'] = $error;
$data['success'] = $success;
$data['data'] ['request'] = $request;
$data['data'] ['fieldslist'] = $searchResultFieldsList;
$data['data'] ['compcount'] = $compcount;

header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
exit();