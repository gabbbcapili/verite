<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilities extends Model
{

    public static function getFeatherIcons(){
        return [
            'Edit' => 'edit',
            'Show' => 'eye',
            'Delete' => 'trash',
            'Approve' => 'check',
            'Default' => 'thumbs-up',
        ];
    }

    public static function actionDropdown($dropdowns){
        $html = '<div class="dropdown">
                  <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown" data-bs-boundary="window">
                    <i data-feather="more-vertical"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end">';
        $icons = static::getFeatherIcons();
        foreach($dropdowns as $action){
            $type =   array_key_exists('type', $action) ? $action['type'] : 'modal_button';
            $title =   array_key_exists('title', $action) ? $action['title'] : '';
            $html .=' <a class="dropdown-item '. $type .'" href="#" data-title="'. $title .'" data-action="'. $action['route'] . '">
                          <i data-feather="'. $icons[$action['name']] .'" class="me-50"></i>
                          <span>'. $action['name'] .'</span>
                       </a>';
        }
        $html .= '</div></div>';
        return $html;
    }

    public static function actionButtons($buttons){
      $icons = static::getFeatherIcons();
      $html = '';
        foreach($buttons as $action){
            $type =   array_key_exists('type', $action) ? $action['type'] : 'modal_button';
            $title =   array_key_exists('title', $action) ? $action['title'] : '';
            if($type == 'modal_button'){
              $html .= '<a href="#" data-bs-toggle="tooltip" data-placement="top" title="'. $title .'"" data-action="'. $action['route'] . '" class="me-75 '. $type .'"><i data-feather="'. $icons[$action['name']] .'"></i>';
            }elseif($type == 'approve'){
              $html .= '<a href="#" data-bs-toggle="tooltip" data-placement="top" title="'. $title .'"" data-action="'. $action['route'] . '" class="me-75 confirm" data-title="Are you sure to approve this?" ><i data-feather="'. $icons[$action['name']] .'"></i>';
            }else{
              $html .= '<a href="'. $action['route'] .'" data-bs-toggle="tooltip" data-placement="top" title="'. $title .'"" data-href="'. $action['route'] . '" class="me-50"><i data-feather="'. $icons[$action['name']] .'"></i>';
            }
        }
        return $html;
    }
}