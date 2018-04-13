<?php
class BuildTreeArray
{
    private $root = 0; //最顶层fid
    private $data = array(); //源数据
    private $treeArray = array(); //属性数组

    function __construct($data,$table,$idKey,$fidKey,$root) {
        if($idKey) $this->idKey = $idKey;
        if($fidKey) $this->fidKey = $fidKey;
        if($root) $this->root = $root;
        if($data) {
            $this->data = $data;
            $this->getChildren($this->root);
        }
    }

    public function getTreeArray()
    {
        return array_values($this->treeArray);
    }

    private function getChildren($root)
    {
        foreach ($this->data as &$node){
            if($root == $node[$this->fidKey]){
                $node['children'] = $this->getChildren($node[$this->idKey]);
                $children[] = $node;
            }
            if($this->root == $node[$this->fidKey]){
                $this->treeArray[$node[$this->idKey]] = $node;
            }
        }
        return $children;
    }
}
?>
