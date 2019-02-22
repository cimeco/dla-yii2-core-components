<?php


namespace quoma\core\modules\menu\components;

use quoma\core\modules\menu\models\MenuLocation;
use yii\base\Widget;

/**
 * Description of MenuLocationWidget
 *
 * @author juan
 */
class MenuLocationWidget extends Widget {
    
    public $slug;
    
    public $name;
    
    public $description;

    public $site_id;
    
    public $menu_class = 'nav navbar-nav';

    public $item_class;

    public $anchor_class;

    public $child_class;
    
    public function run(){
        // Busco el MenuLocation por el slug recibido 
        $location = MenuLocation::findOne(['slug' => $this->slug, 'site_id' => $this->site_id]);
        
        //Si el MenuLocation no existe lo creo. Inicialmente no tendra un menú asociado por lo que no mostrara ningún menú
        if ($location === null) {
            $location= new MenuLocation();
            $location->name= $this->name;
            $location->description = $this->description;
            $location->slug= $this->slug;
            $location->site_id= $this->site_id;

            $location->scenario= 'front-save';

            if (!$location->save() && YII_ENV === 'dev') {
                return '<div class="alert alert-warning">No se pudo crear la ubicación de menu '.  $this->name . '</div>';
            }
        }
        
        return '<div class="'. $this->slug .'_menu_location">' . (!empty($location->menu) ? $location->menu->render(false, $this->menu_class, $this->item_class, $this->anchor_class, $this->child_class): '') . '</div>';
    }
}
