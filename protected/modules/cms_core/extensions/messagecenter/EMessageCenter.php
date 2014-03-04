<?php
/**
 * EMessageCenter class file.
 *
 * @author MetaYii
 * @version 1.0
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2009 MetaYii
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * The MIT License
 *
 * Copyright (c) 2009 MetaYii
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * EMessageCenter is a notifier similar to the notification bars used by the
 * major web browsers (Firefox & MSIE)
 *
 * @author MetaYii
 */
class EMessageCenter extends CWidget
{
   //***************************************************************************
   // Configurable options
   //***************************************************************************

   /**
    * The id of the widget
    *
    * @var string
    */
   private $id = 'message_center';

   /**
    * A string that contain the message to be displayed. It will be
    * automagically quoted.
    *
    * @var string
    */
   private $body = null;

   /**
    * Delay of the scrolling which make the box to appear.
    *
    * @var string
    */
   private $delay = 2500;

   /**
    * The image for the close button.
    *
    * @var string
    */
   private $closeImage = '';

   /**
    * The alternate label for the cluse button. It will be automagically quoted.
    *
    * @var string
    */
   private $closeLabel = 'x';

   /**
    * You can use the bundled CSS file, or create your own based on the bundled.
    *
    * @var boolean
    */
   private $useBundledStyleSheet = true;

   //***************************************************************************
   // Setters and getters
   //***************************************************************************

   /**
    * Setter
    *
    * @param string $value id
    */
   public function setId($value)
   {
      $this->id = strval($value);
   }

   /**
    * Getter
    *
    * @return string
    */
   public function getId()
   {
      return $this->id;
   }

   /**
    * Setter
    *
    * @param string $value body
    */
   public function setBody($value)
   {
      $this->body = strval($value);
   }

   /**
    * Getter
    *
    * @return string
    */
   public function getBody()
   {
      return $this->body;
   }

   /**
    * Effect
    *
    * @param string $value effect
    */
   public function setDelay($value)
   {
      if (!is_integer($value) || ($value < 0))
         throw new CException(Yii::t('EMessageCenter', 'delay must be a positive integer'));
      $this->delay = $value;
   }

   /**
    * Effect
    *
    * @return string
    */
   public function getDelay()
   {
      return $this->delay;
   }

   /**
    * Setter
    *
    * @param string $value closeImage
    */
   public function setCloseImage($value)
   {
      $this->closeImage = strval($value);
   }

   /**
    * Getter
    *
    * @return string
    */
   public function getCloseImage()
   {
      return $this->closeImage;
   }

   /**
    * Setter
    *
    * @param string $value closeLabel
    */
   public function setCloseLabel($value)
   {
      $this->closeLabel = strval($value);
   }

   /**
    * Getter
    *
    * @return string
    */
   public function getCloseLabel()
   {
      return $this->closeLabel;
   }

   /**
    * Setter
    *
    * @param boolean $value useBundledStyleSheet
    */
   public function setUseBundledStyleSheet($value)
   {
      if (!is_bool($value))
         throw new CException(Yii::t('EFaceBox', 'useBundledStyleSheet must be boolean'));
      $this->useBundledStyleSheet = $value;
   }

   /**
    * Getter
    *
    * @return boolean
    */
   public function getUseBundledStyleSheet()
   {
      return $this->useBundledStyleSheet;
   }

   //***************************************************************************
   // Utilities
   //***************************************************************************

   protected function makeOptions()
   {
      $options = array();

      $options['id'] = $this->id;
      $options['delay'] = $this->delay;

      return CJavaScript::encode($options);
   }

   //***************************************************************************
   // Run Lola, Run
   //***************************************************************************

   /**
    * Initialize the widget
    */
   public function init()
   {      
      ob_start();
   }

   /**
    * Run the widget
    */
   public function run()
   {
      if (is_null($this->body)) {
         $this->body = ob_get_contents();
         ob_end_clean();
      }
      else {
         ob_end_flush();
      }

      $baseUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'jquery');
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile($baseUrl.'/jquery.message-center.js');
      if ($this->useBundledStyleSheet) {
         $cs->registerCssFile($baseUrl.'/jquery.message-center.css');
      }

      $options = $this->makeOptions();
      $script =<<<EOP
$().message_center({$options});
EOP;
      $cs->registerScript('Yii.'.get_class($this), $script, CClientScript::POS_READY);

      $this->closeImage = ($this->closeImage==='') ? $baseUrl.'/images/close.png' : $this->closeImage;

      $image = CHtml::tag('img', array('id'=>$this->id.'_close', 'src'=>$this->closeImage, 'alt'=>$this->closeLabel));
      $html  = CHtml::tag('div', array('id'=>$this->id), $this->body.$image, true);

      echo $html;
   }
}