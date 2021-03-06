<?php
namespace ZF2DoctrineBase\Controller;

use ZF2DoctrineBase\Form\BuscadorForm;
use Zend\View\Model\ViewModel;

abstract class BaseCrudDoctrineController extends BaseDoctrineController{

  //Campos obligatorios que deben ser provistos por el desarrollador de las clases hijas
  protected $campoPK;     //String para indicar el nombre del campo(s) de la clave primaria de la entidad.
  protected $urlBase;     // Identificador de la URL Base definida en module-config (usando child-routes)

  protected $entityFieldsIndex;  //Array de campos a mostrar en el Index
  protected $entityTitlesIndex;  //Array de titulos de los campos a mostrar en el Index
  protected $titlePage;          //String que contiene el titulo de cada pagina
  protected $formFieldsWidth;    //Array con los anchos de los campos a usar en el form. Se usa en conjunto con col-md-* (bootstrap3)

  //Campos opcionales que pueden ser provistos por el desarrollador de las clases hijas
  protected $partialForm;        //Url del Form a usar puede ser personalizado (opcional) o usar el que esta en view/base-crud
  protected $indexPartialTable;  //Url del Parcial a usar para la tabla del index, puede ser personalizado (opcional) o usar el que esta en view/base-crud
  protected $useBuscador;        //campo booleano que indica si se usa buscador ó no (true por defecto)
  protected $numRegistrosPagina; //Nro de Registros por pagina en el Index (10 default)

  protected $formRequireSelect2;  //Indica si el Form por defecto requiere usar Select2 (false por defecto)
  protected $formRequireDatePicker; //Indica si el Form por defecto requiere usar DatePicker (false por defecto)
  protected $formJsFile;  // Ruta dentro de public/js del archivo Javascript a incluir en el form
  //Nota: Si usa Form Personalizado no es necesario usar ninguna de las ultimas 3 variables

  //Campos de uso interno
  protected $classEntity; //Alias para la clase de la entidad definido en module-config para el Service Manager.
  protected $classModel;  //Alias para la clase del modelo definido en module-config para el Service Manager.
  protected $classForm;   //Alias para la clase del form definido en module-config para el Service Manager.

  protected $urlController;      //Nombre Corto del Controlador(en minusculas) para construir las urls usando child-routes.

  protected $model;     //referencia al modelo asociado a la entidad que se le hace el CRUD
  protected $viewModel; //viewModel usado para las vistas

  protected $entity;  //instancia de la entidad que se esta manejando

  const URL_BASE_VIEW='base-crud-doctrine/';

  public function __construct($modulo){
     $this->classEntity=$modulo.'Entity';
     $this->classModel=$modulo.'Model';
     $this->classForm=$modulo.'Form';
     $this->viewModel=new \Zend\View\Model\ViewModel();
     $this->partialForm=self::URL_BASE_VIEW.'form.phtml';
     $this->indexPartialTable=self::URL_BASE_VIEW.'index-table-partial.phtml';
     $this->entityFieldsIndex=array();
     $this->entityTitlesIndex=array();
     $this->formFieldsWidth=array();
     $this->useBuscador=false;
     $this->urlController=strtolower($modulo);
     $this->numRegistrosPagina=10;
     $this->formRequireSelect2=false;
     $this->formRequireDatePicker=false;
     $this->formJsFile=null;
     $this->campoPK='id';
  }

  public function indexAction()    {
    $page= (int)$this->params()->fromQuery('page', 1);
    $fieldSort= $this->params()->fromQuery('sort',null);
    $orderSort= $this->params()->fromQuery('order','asc');

    //Se obtiene la data a mostrar en el index
    $lista_pag = $this->getListDataToIndex($page,$fieldSort,$orderSort);

    //Se establece el sentido del ordenamiento de acuerdo al valor previo: asc->desc,desc->asc
    $arrSort=array();
    foreach ($this->entityFieldsIndex as $field) {
          $arrSort[$field]='asc';
          if($field == $fieldSort)
            if ($orderSort=='asc') $arrSort[$field]='desc';
    }

    $param= array('indexPartialTable' => $this->indexPartialTable,
                  'paginator' => $lista_pag,
                  'list_fields' => $this->entityFieldsIndex,
                  'list_titles' =>  $this->entityTitlesIndex,
                  'title' => $this->titlePage,
                  'campoPK' => $this->campoPK,
                  'urlBase' => $this->urlBase,
                  'urlController' => $this->urlController,
                  'arrSort' => $arrSort,
     );
    if ($this->useBuscador)
       $param['form']= new BuscadorForm(); 
 

    $this->viewModel->setVariables($param);
    $this->viewModel->setTemplate(self::URL_BASE_VIEW.'index.phtml');
    return $this->viewModel;
  }

 /**
 *  getListDataToIndex
 *  Método que genera la data a mostrar en el index
 *  Este metodo se puede sobreescribir si se quieren agregar condiciones de filtro y otros aspectos
 */
  protected function getListDataToIndex($page,$fieldSort,$orderSort){
        $lista_pag = $this->getModel()->listPaginate($page,
                                                     $this->numRegistrosPagina,
                                                     $fieldSort,
                                                     $orderSort);
        return $lista_pag;
  }


  public function createAction(){
       //Obtengo una instancia del Entity
      $this->entity = $this->getServiceLocator()->get($this->classEntity);

      $form = $this->getForm('Guardar');
      $form->bind($this->entity);

      $request = $this->getRequest();
      if ($request->isPost()){
          $form->setData($request->getPost());
          if($form->isValid()){ // si el formulario es valido

              //Patch agregado pq cuando la pk existe el entity tiene todos los campos en null
              // Problema con el DoctrineHydrator https://github.com/doctrine/DoctrineModule/issues/261
              $this->patchVerifyAndFixEntity($this->entity,$form->getData());

              $ok=$this->getModel()->validate($this->entity);
              if($ok){
                  $ok = $this->getModel()->save($this->entity);
                  if($ok){
                      $this->flashMessenger()->addMessage('Inclusion realizada Exitosamente!');
                      return $this->redirectToIndex();
                  }
              }
              if(!$ok) $this->setMessagesErrorModelToForm();
         }
      }
      // llama a un procedimiento definido por el usuario (opcional)
      $this->beforeShowCreate();

      $this->viewModel->setVariables($this->getVariablesToForm());
      $this->viewModel->setTemplate(self::URL_BASE_VIEW.'create.phtml');
      return $this->viewModel;
    }

    private function getVariablesToForm($id=null)
    {
        $arr=[  'campoPK' =>$this->campoPK,
                'form' => $this->form,
                'title' => $this->titlePage,
                'urlBase' => $this->urlBase,
                'urlController' => $this->urlController,
                'partialForm' =>$this->partialForm,
                'formFieldsWidth'=> $this->formFieldsWidth,
                'formRequireDatePicker' =>$this->formRequireDatePicker,
                'formRequireSelect2' =>$this->formRequireSelect2,
                'formJsFile' =>$this->formJsFile,
             ];
        if(!is_null($id)) $arr['id']=$id;
        return $arr;
    }
                
    public function deleteAction(){
      $id = $this->params($this->campoPK);
      if(!isset($id)) return $this->redirectToIndex();

      $this->entity = $this->getModel()->get($id); // obtengo la entidad
      if($this->entity){
        $ok = $this->getModel()->remove($this->entity);
        if($ok) $msg = "Eliminacion realizada con Exito!";
        else{
            $arr_msg = $this->getModel()->getErrorMessages();
            $msg = $arr_msg['id'][0];
        }
        $this->flashMessenger()->addMessage($msg);
      }
      return $this->redirectToIndex();
    }


  public function editAction(){
      $id = $this->params($this->campoPK);
      if(!isset($id)) return $this->redirectToIndex();

      $this->entity= $this->getModel()->get($id);
      $form = $this->getForm('Actualizar');

      if ($this->entity){
          $form->bind($this->entity);
          $request = $this->getRequest();
          if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $ok = $this->getModel()->save();
                if($ok){
                    $this->flashMessenger()->addMessage('Modificacion Realizada Exitosamente');
                    return $this->redirectToIndex();
                 }
                 else $this->setMessagesErrorModelToForm();
            }
          }
      }
      $this->beforeShowEdit();

      $this->viewModel->setVariables($this->getVariablesToForm($id));
      $this->viewModel->setTemplate(self::URL_BASE_VIEW.'edit.phtml');
      return $this->viewModel;
  }

  public function filterAction(){
      $valueFilter = $this->getRequest()->getQuery($this->campoPK);
      $arrResult=$this->getModel()->filterModel("%".$valueFilter."%");

      $response= $this->getResponse();
      $response->setContent(\Zend\Json\Json::encode($arrResult));
      return $response;
  }

  protected function getModel(){
    if(!$this->model){
        $this->model =$this->getServiceLocator()->get($this->classModel);
        $this->model->setObjectManager($this->getObjectManager());
    }
    return $this->model;
  }

  protected function getForm($modo){
    $this->form = $this->getServiceLocator()->get($this->classForm);

    //Obtengo una instancia del Entity
    $entity = $this->getServiceLocator()->get($this->classEntity);
    $this->form->setObjectManager($this->getObjectManager());
    $this->form->setEntity($entity);
    $this->form->setupGui();

    $this->form->get('submit')->setAttribute('value',$modo);
    return $this->form;
  }

  // funcion a ejecutar antes de mostrar el formulario de Create
  // debe ser sobreescrita por las clases hijas si es requerido
  protected function beforeShowCreate(){
  }


  // funcion a ejecutar antes de mostrar el formulario de Edit
  // debe ser sobreescrita por las clases hijas si es requerido
  protected function beforeShowEdit(){
  }


    //Patch agregado pq cuando la pk existe el entity tiene todos los campos en null
    // Problema con el DoctrineHydrator
    //https://github.com/doctrine/DoctrineModule/issues/261

    protected function patchVerifyAndFixEntity($entity,$dataForm){
        //$this->doctrineDebug($form->getData());
        //$this->doctrineDebug($entity);
        $isNullEntity=false;
        $field=$this->campoPK;
        if(is_null($entity->$field)) $isNullEntity=true;

        if($isNullEntity){
           $arrFields = $entity->getArrayProperties();
           foreach ($arrFields as $field)
               $entity->$field=$dataForm->$field;
        }
        //$this->doctrineDebug($dataForm);
        //$this->doctrineDebug($entity);
    }

    protected function redirectToIndex()
    {
        return $this->redirect()->toRoute($this->urlBase,['controller'=>$this->urlController]);
    }

    protected function setMessagesErrorModelToForm()
    {
      $arr_msg = $this->getModel()->getErrorMessages();
      $pkField=$this->campoPK;
      
      if(is_null($this->form->getBaseFieldset())) $base=$this->form;
      else $base=$this->form->getBaseFieldset();

      foreach ($arr_msg as $key => $msgArr)
         if ($base->has($key)) $base->get($key)->setMessages($msgArr);

      //$base->get($pkField)->setMessages(array_merge($arr_msg[$pkField],$arr_msg['pk']));
    }
}
?>