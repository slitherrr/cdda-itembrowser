<?php

class Item
{
  private $data;
  public function __construct($data)
  {
    $this->data = $data;
  }

  public function __get($name)
  {
    $method = "get".ucfirst($name);
    if(method_exists($this, $method))
    {
      return $this->$method();
    }
    try {
      return $this->data->{$name};
    } catch(Exception $e)
    {
      return "N/A";
    }
  }

  public function getRecipes()
  {
    if(!isset($this->data->recipes))
      return [];

    return array_map(function($recipe)
        { return Recipes::get($recipe); }
    , $this->data->recipes);
  }

  public function getToolFor()
  {
    if(!isset($this->data->toolFor))
      return [];
    return array_map(function($recipe)
        { return Recipes::get($recipe); }
    , $this->data->toolFor);
  }

  public function getIsArmor()
  {
    return isset($this->data->covers);
  }

  public function protection($type)
  {
    $mat1 = Materials::get($this->material[0]);
    $mat2 = Materials::get($this->material[1]);

    $variable = "{$type}_resist";
    $thickness = $this->material_thickness;
    if($mat2=="null")
    {
      return $thickness*3*$mat1->$variable;
    }
    else
    {
      return $thickness*(($mat1->$variable*2)+$mat2->$variable);
    }
  }

  public function getWeight()
  {
    $weight = $this->data->weight;
    return number_format($weight/1000, 2)."kg/".number_format($weight/453.6,2)."lbs";
  }

  public function getMovesPerAttack()
  {
    return ceil(65 + 4 * $this->data->volume + $this->data->weight / 60);
  }

  public function getSkillsRequired()
  {
    return isset($recipe->skills_required)? 
    is_array($recipe->skills_required[0])? 
      array_map(function($i) { return "$i[0]($i[1])"; }, $recipe->skills_required): 
      array("{$recipe->skills_required[0]}({$recipe->skills_required[1]})"):
    array("N/A");
  }
}
