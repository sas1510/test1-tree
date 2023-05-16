<?php

// рекурсивно выбирает все поля из отдельно взятого документа, возвращает массив полей
    function getFieldsListRecurcive ($sorce) {
        $result = array();
        foreach ($sorce as $key => $value) {
            if (gettype($value) !== 'array') {
                $result[] = strval($key);
            } else {
                $result[] = strval($key);
                $result = array_merge($result, getFieldsListRecurcive($value));
            }
        }
        return $result;
    }

// выбирает все поля документа сливая их в единый массив без дубликатов (исхожу из того, что документы могут иметь не одинаковую структуру)
    function getFieldsList ($sorce) {
        $result = array();
        foreach ($sorce as $value) {
            foreach (getFieldsListRecurcive($value) as $_value) {
                if (!in_array( $_value, $result)) {
                    $result[] = $_value;
                }
            }
        }
        return $result;
    }

// Функция рекурсивного поиска внутри документа, возвращает true в случае обнаружения искомого значения
    function recursiveSearch ($sorce, $field, $searchData) {
        foreach ($sorce as $key => $value) {
            $GLOBALS['comparisonCount']++;
            if (gettype($value) <> 'array') {
                if (strval($value) === strval($searchData) && (strval($key) === strval($field))){
                    return true;
                }
            } else {
                return recursiveSearch($value, $field, $searchData);
            }
        }
    }

// Функция поиска документа содержащего искомое значение, возвращает масив с ключами документов в исходном масиве
    function search($sorce,$field, $searchData) {
        $result = array();
        foreach ($sorce as $masterKey => $value) {
                if (recursiveSearch($value, $field, $searchData)) {
                    $result[] = $masterKey;
                }
            }
        return $result;
    }