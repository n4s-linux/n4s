<?php

    class dataSource
    {
       public function getFieldNames()
        {
            return $this->fieldNames;
        }
    }

    class fieldHandler
    {
        public function toString($value)
        {
            return $value;
        }
    }

    class monetaryValueFieldHandler extends fieldHandler
    {
        public function toString($value)
        {
            return number_format($value, 2, ".",",");
        }
    }

    class myFieldHandler extends monetaryValueFieldHandler
    {
        public function toString($value)
        {
            return parent::toString($value)." DKK";
        }
    }

    class arrayDataSource extends dataSource
    {
        public function __construct($data)
        {
            $this->data = $data;
        }

        public function getData()
        {
            return $this->data;
        }
    }

    class jsonDataSource extends arrayDataSource
    {
        public function __construct($json)
        {
            $this->data = json_decode($json);
        }
    }

    class Datagrid
    {
        public function __construct(dataSource $dataSource)
        {
            $this->dataSource = $dataSource;
            $this->stpl = new \Mikjaer\SimpleTpl\SimpleTpl();
        }

        public function dataSource()
        {
            return $this->dataSource;
        }

        public function render()
        {
            $fieldNames = $this->fieldNames;
            $data = $this->dataSource->getData();
           
            foreach ($fieldNames as $field)
                $fieldLabels[] = $this->field($field)->getLabel();

            $this->stpl->assign("fieldLabels", $fieldLabels);
            $this->stpl->assign("fieldNames", $fieldNames);

            foreach ($data as $rowid=>$row)
            {
                $result = array();
                foreach ($fieldNames as $field)
                    $result[] = $this->field($field)->getHandler()->toString( $row[$field]);
                $this->stpl->append("data", $result);
            }

            return $this->stpl->fetch(__DIR__."/grid.tpl");

            return "OK";
        }

        public function setFields($names)
        {
            $this->fieldNames = $names;
        }
    
 

        public function registerFieldHandler($field, fieldHandler $handler)
        {
            $this->fieldHandlers[$field] = $handler;
        }
    
        public function field($field)
        {
            if (!isset($this->fields[$field]))
                $this->fields[$field] = new fieldObject($field);

            return $this->fields[$field];
        }
    }

    class fieldObject
    {
        public function __construct($fieldName)
        {
            $this->name = $fieldName;
        }
            // FieldHandler
        public function registerHandler(fieldHandler $handler) { $this->handler = $handler; return $this; }
        public function getHandler()
        {
            if (!isset($this->handler))
                $this->handler = new fieldHandler();

            return $this->handler;
        }

            // Label
        public function setLabel($label) { $this->label = $label; return $this; }
        public function getLabel()
        {
            if (!isset($this->label))
                $this->label = ucfirst($this->name);

            return $this->label;
        }
    }

