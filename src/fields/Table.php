<?php
namespace verbb\feedme\fields;

use verbb\feedme\base\Field;
use verbb\feedme\base\FieldInterface;
use verbb\feedme\helpers\BaseHelper;

use Craft;
use craft\helpers\Localization;

use Cake\Utility\Hash;

class Table extends Field implements FieldInterface
{
    // Properties
    // =========================================================================

    public static $name = 'Table';
    public static $class = 'craft\fields\Table';


    // Templates
    // =========================================================================

    public function getMappingTemplate()
    {
        return 'feed-me/_includes/fields/table';
    }


    // Public Methods
    // =========================================================================

    public function parseField()
    {
        $preppedData = [];
        $rowCounter = [];

        $columns = Hash::get($this->fieldInfo, 'fields');

        foreach ($this->feedData as $nodePath => $value) {
            foreach ($columns as $columnHandle => $columnInfo) {

                // Strip out array numbers in the feed path like: MatrixBlock/0/Images/0. We use this to get the field
                // its supposed to match up with, which is stored in the DB like MatrixBlock/Images
                $feedPath = preg_replace('/(\/\d+\/)/', '/', $nodePath);
                $feedPath = preg_replace('/^(\d+\/)|(\/\d+)/', '', $feedPath);

                $node = Hash::get($columnInfo, 'node');
                $handle = Hash::get($columnInfo, 'handle');
                $type = Hash::get($columnInfo, 'type');

                if ($feedPath == $node || $nodePath == $node) {
                    if (!isset($rowCounter[$columnHandle])) {
                        $rowCounter[$columnHandle] = 0;
                    } else {
                        $rowCounter[$columnHandle]++;
                    }

                    $parsedValue = $this->_handleSubField($type, $value);

                    $preppedData[$rowCounter[$columnHandle]][$columnHandle] = $parsedValue;
                }
            }
        }

        return $preppedData;
    }


    // Private Methods
    // =========================================================================

    private function _handleSubField($type, $value)
    {
        if ($type == 'checkbox') {
            return BaseHelper::parseBoolean($value);
        } else if ($type == 'color') {

        } else if ($type == 'date') {

        } else if ($type == 'lightswitch') {
            return BaseHelper::parseBoolean($value);
        } else if ($type == 'multiline') {

        } else if ($type == 'number') {
            return Localization::normalizeNumber($value);
        } else if ($type == 'singleline') {

        } else if ($type == 'time') {

        }

        return $value;
    }

}