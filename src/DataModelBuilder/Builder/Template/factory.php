<?php print_php_header() ?>

namespace <?php echo $appNamespace . '\\' . $classNamespace ?>;

<?php foreach ($uses as $use): ?>
use <?php echo $use; ?>;
<?php endforeach; ?>

/**
 * <?php echo $className ?> is a data factory utility class for
 * the "<?php echo $classFQRN; ?>" relation
 */
<?php if (isset($classModifier)) echo $classModifier . ' '; ?>class <?php echo $className ?> extends <?php echo $classBaseClass; ?> {

<?php if (isset($generate)):?>
    public function __construct(Database $database) {
        parent::__construct($database, '<?php echo $modelClass?>');
        $this->relation = '<?php echo $classFQRN?>';
<?php if(isset($fieldConverter)):?>
        $this->fieldConverter = new <?php echo "{$fieldConverter}(['datetimeFormat' => DateTimeConversion::SETTINGS])";?>;
<?php endif;?>
    }

<?php if(count($converters) !== 0):?>
    protected function convertFromRecord(array $record) {
<?php foreach($converters as $field => $converterlist):?>
<?php foreach($converterlist as $converter):?>
        $this->fieldConverter->fromRecord($record, '<?php echo $field;?>', <?php echo is_string($converter) ? "'{$converter}'" :  $converter; ?>);
<?php endforeach;?>
<?php endforeach;?>
        return $record;
    }

    protected function convertFromModel(array $data) {
<?php foreach($converters as $field => $converterlist):?>
<?php foreach($converterlist as $converter):?>
        $this->fieldConverter->fromModel($data, '<?php echo $field;?>', <?php echo is_string($converter) ? "'{$converter}'" :  $converter; ?>);
<?php endforeach;?>
<?php endforeach;?>
        return $data;
    }
<?php endif;?>
<?php endif;?>
    /**
     * Returns a new instance of <?php echo $modelClass."\n"?>
     * @return <?php echo $modelClass."\n"?>
     */
    public function newModel() {
        return parent::newModel();
    }

<?php if(isset($uniqueKeys) && count($uniqueKeys) !== 0):?>
<?php foreach($uniqueKeys as $key):?>
    /**
     * Retuns a single <?php echo $modelClass?> model
<?php foreach($key['functionParamsDoc'] as $param):?>
     * @param <?php echo $param[0].' '.$param[1]."\n"?>
<?php endforeach;?>
     * @return <?php echo $modelClass."\n"?>
     */
    public function getBy<?php echo $key['functionName'];?>(<?php echo $key['functionParams'];?>) {
        return $this->getByOne(self::ALL_COLUMNS, [<?php echo $key['functionCallParam']?>]);
    }
<?php if($is_writable):?>

    /**
     * Deletes a single record (<?php echo $classFQRN?>)
<?php foreach($key['functionParamsDoc'] as $param):?>
     * @param <?php echo $param[0].' '.$param[1]."\n"?>
<?php endforeach;?>
     * @return <?php echo $modelClass."\n"?>
     */
    public function deleteBy<?php echo $key['functionName'];?>(<?php echo $key['functionParams'];?>) {
        return $this->deleteByOne([<?php echo $key['functionCallParam']?>]);
    }
<?php endif;?>

<?php endforeach;?>
<?php endif;?>
<?php if(isset($multiKeys) && count($multiKeys) !== 0):?>
<?php foreach($multiKeys as $key):?>
    /**
     * Returns <?php echo $modelClass?> models
<?php foreach($key['functionParamsDoc'] as $param):?>
     * @param <?php echo $param[0].' '.$param[1]."\n"?>
<?php endforeach;?>
     * @return <?php echo $modelClass."\n"?>
     */
    public function getManyBy<?php echo $key['functionName'];?>(<?php echo $key['functionParams'];?>) {
        return $this->getManyBy(self::ALL_COLUMNS, [<?php echo $key['functionCallParam']?>]);
    }
<?php if($is_writable):?>

    /**
     * Deletes <?php echo $modelClass?> models
<?php foreach($key['functionParamsDoc'] as $param):?>
     * @param <?php echo $param[0].' '.$param[1]."\n"?>
<?php endforeach;?>
     * @return <?php echo $modelClass."\n"?>
     */
    public function deleteManyBy<?php echo $key['functionName'];?>(<?php echo $key['functionParams'];?>) {
        return $this->getManyBy(self::ALL_COLUMNS, [<?php echo $key['functionCallParam']?>]);
    }
<?php endif;?>
    
<?php endforeach;?>
<?php endif;?>
}
