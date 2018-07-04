<?php

use yii\db\Migration;

class m160811_203137_pdf_service_config extends Migration
{
    public function init() {
        $this->db = 'db_config';
        parent::init();
    }

    public function up()
    {

        $category= \quoma\modules\config\models\Category::findOne(['name' => 'General']);

        if(!$category){
            $category= new \quoma\modules\config\models\Category(['name' => 'General', 'status' => 'enabled']);

            $category->save();
        }

        $this->insert('item', [
            'attr' => 'wkhtmltopdf_docker_host',
            'type' => 'textInput',
            'label' => 'Host para servicio wkhtmltopdf ',
            'description' => '',
            'multiple' => 0,
            'category_id' => $category->category_id,
            'superadmin' => 1,
            'default' => 'http://127.0.0.1/'
        ]);

        $this->insert('item', [
            'attr' => 'wkhtmltopdf_docker_port',
            'type' => 'textInput',
            'label' => 'Puerto para servicio wkhtmltopdf ',
            'description' => '',
            'multiple' => 0,
            'category_id' => $category->category_id,
            'superadmin' => 1,
            'default' => '5001'
        ]);

    }

    public function down()
    {
        echo "m160811_203137_pdf_service_config cannot be reverted.\n";

        return false;
    }

}