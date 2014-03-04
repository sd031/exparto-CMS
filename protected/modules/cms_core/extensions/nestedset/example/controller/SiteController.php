<?php

class SiteController extends CController
{

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		public function actionIndex()
    	{
    		// renders the view file 'protected/views/site/index.php'
    		// using the default layout 'protected/views/layouts/main.php'
    		ob_start();

            $root = Tree::model()->findByPK(1);

            $newNode = new Tree();
            $newNode->name = "First Node";
            $root->appendChild($newNode); //You do not have to use the "save" function here.

            $newNode2 = new Tree();
            $newNode2->name = "Second Node";
            $root->appendChild($newNode2); //You do not have to use the "save" function here.

            $newNode3 = new Tree();
            $newNode3->name = "GrandChild Node";
            $newNode->appendChild($newNode3); //You do not have to use the "save" function here.

            echo "<h3>Start Tree</h3>";
            $tree2 = $root->getNestedTree();
            foreach($tree2 as $key => $subtree)
            {
                echo $key.": ".$this->printNestedTree($subtree);
            }

            // Let's do some modifications:

            $newNode2->moveLeft(); //You do not have to use the "save" function here.
            echo "<h3>Move Second Node to the left</h3>";
            $tree2 = $root->getNestedTree();
            foreach($tree2 as $key => $subtree)
            {
                echo $key.": ".$this->printNestedTree($subtree);
            }

            $newNode3->moveUp(); //You do not have to use the "save" function here.
            echo "<h3>Move the GrandChild node up</h3>";
            $tree2 = $root->getNestedTree();
            foreach($tree2 as $key => $subtree)
            {
                echo $key.": ".$this->printNestedTree($subtree);
            }

            $newNode3->moveBelow($newNode2);
            echo "<h3>Move GrandChild Down to Second</h3>";
            $tree2 = $root->getNestedTree();
            foreach($tree2 as $key => $subtree)
            {
                echo $key.": ".$this->printNestedTree($subtree);
            }

            $newNode2->moveRight();
            echo "<h3>Move Second to the right</h3>";
            $tree2 = $root->getNestedTree();
            foreach($tree2 as $key => $subtree)
            {
                echo $key.": ".$this->printNestedTree($subtree);
            }

            $newNode3->moveBefore($newNode);
            echo "<h3>Move GrandChild before the first node</h3>";
            $tree2 = $root->getNestedTree();
            foreach($tree2 as $key => $subtree)
            {
                echo $key.": ".$this->printNestedTree($subtree);
            }

    		$newNode->moveBelow($newNode2);
            echo "<h3>Move First Node below the second</h3>";
            $tree2 = $root->getNestedTree();
            foreach($tree2 as $key => $subtree)
            {
                echo $key.": ".$this->printNestedTree($subtree);
            }

            $newNode2->deleteNode();
            echo "<h3>Delete only the Second Node</h3>";
            $tree2 = $root->getNestedTree();
            foreach($tree2 as $key => $subtree)
            {
                echo $key.": ".$this->printNestedTree($subtree);
            }

            $newNode->moveBelow($newNode3);
            echo "<h3>Move First Node below the GrandChild</h3>";
            $tree2 = $root->getNestedTree();
            foreach($tree2 as $key => $subtree)
            {
                echo $key.": ".$this->printNestedTree($subtree);
            }

            $newNode3->deleteNode(true);
            echo "<h3>Delete the GrandChild and all children</h3>";
            $tree2 = $root->getNestedTree();
            foreach($tree2 as $key => $subtree)
            {
                echo $key.": ".$this->printNestedTree($subtree);
            }
    		$message = ob_get_clean();

    		$this->render('index',array('message' => $message));
    	}
	}
	
	private function printNestedTree($tree)
	{
	    
	    $result = "<strong>".$tree['node']->name."</strong> (".$tree['node']->getLeftValue().",".$tree['node']->getRightValue().")";
	    if(is_array($tree['children']))
	    {
	        $result .= "<ul>";
	        foreach($tree['children'] as $key => $child)
    	    {
    	        $result .= "<li>";
    	        $result .= $key.": ".$this->printNestedTree($child);
    	        $result .= "</li>";
    	    }
    	    $result .= "</ul>";
	    }
	    
	    return $result;
	}

}