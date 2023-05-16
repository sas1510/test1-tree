<?php
// построить бинарное дерево по указанному полю
    function buildBinaryTree($tree, $sorce, $field) {
        $treeData = array();
        $treeData = getBinaryTreeData($sorce, $field)['values'];
        if (is_array($treeData)) {
            foreach ($treeData as $key => $value) {
                $tree->insert($value, $key);
            }
        }
    }
//выбрать значение указанного поля из одного документа
    function selectValueByField($sorce, $field) {
        $result = '';
        foreach ($sorce as $key => $value) {
            if (!is_array($value) && (strval($key) === $field)) {
                $result = ''.$value;
            } else {
               if (is_array($value)) {
                   $result = $result.selectValueByField($value, $field);
               }
            }
        }
        return $result;
    }
//собрать значения со всех документов по указанному полю, ответ - массив
    function getBinaryTreeData($sorce, $field) {
        $_value = '';
        $result = array('field' => $field, 'values' => array());
        //$result['field'] = $field;
        foreach ($sorce as $key => $value) {
            $_value = selectValueByField($value, $field);
            if ($_value !== '') {
                $result['values'][$key] = $_value;
            }
        }
        return $result;
    }


    class BinaryNode                            //Класс узла дерева
    {
        public $parent = null;                           // Родитель
        public $value = 0;                              // значение поля
        public $left = null;                           // левый потомок типа BinaryNode
        public $right = null;                         // правый потомок типа BinaryNode
        public $index = array();                     // массив с индексами элементов в источнике, имеющих значение value

        public function __construct($item, $dataIndex) {
            $this->value = $item;
            $this->index[] = $dataIndex;
            $this->left = null;
            $this->right = null;
            $this->parent = null;
        }

        // симметричный проход текущего узла
        public function dump() {

            if ($this->left !== null) {
                $this->left->dump();
            }
            var_dump([$this->index, $this->value]);
            if ($this->right !== null) {
                $this->right->dump();
            }
        }

        // поиск ноды по значению value, возвращаем масив с индексами документов в источнике
        public function findNode($dataValue)
        {
            if ($this->value > $dataValue) {
                $GLOBALS['BTcomparisonCount']++;
                if ($this->left !== null) {
                    return $this->left->findNode($dataValue);
                }
            } else if ($this->value < $dataValue) {
                $GLOBALS['BTcomparisonCount']++;
                if ($this->right !== null) {
                    return $this->right->findNode($dataValue);
                }
            } else {
                $GLOBALS['BTcomparisonCount']++;
                return $this->index;
            }
        }
    }

//класс самого дерева
    class BinaryTree
    {
        protected $root;                        // корневая нода
        public function __construct() {
            $this->root = null;
        }

        // проверяем, не пуст ли корень
        public function isEmpty() {
            return $this->root === null;
        }

        //
        public function insert($item, $dataIndex) {
            $node = new BinaryNode($item, $dataIndex);
            if ($this->isEmpty()) {
                $this->root = $node;
            }
            else {
                $this->insertNode($node, $this->root, $dataIndex);
            }
        }

        protected function insertNode($node, &$subtree, $dataIndex) {
            if ($subtree === null) {
                $subtree = $node;
            }
            else {
                if ($node->value > $subtree->value) {
                    $node->parent = &$subtree;
                    $this->insertNode($node, $subtree->right, $dataIndex);
                }
                else if ($node->value < $subtree->value) {
                    $node->parent = &$subtree;
                    $this->insertNode($node, $subtree->left, $dataIndex);
                }
                else {
                    // если значение повторяется, добавляем индекс
                    $subtree->index[] = $dataIndex;
                }
            }
        }

        public function traverse() {
            // отображение дерева в возрастающем порядке от корня
            $this->root->dump();
        }

        public function find($value) {
            // поиск значения в дереве, возвращает массив с индексами документов
            return $this->root->findNode($value);
        }

        public function saveToFile($fileName) {
            // сохраним дерево в фаил
            if (!$this->isEmpty()) {
                file_put_contents($fileName, serialize($this->root));
            }
        }

        public function loadFromFile($fileName) {
        }

        public function balance() {
        }
    }