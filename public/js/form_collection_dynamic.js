/*
* Clase con metodos para agregar/remover elementos dinamicamente de un form collection en zf2
* Debe usarse conjuntamente con el View Helper ZF2DoctrineBase/View/Helper/MyFormCollection 
* para visualizar las colecciones.
* 
* @author William Yanez
* Basado en: http://stackoverflow.com/questions/17211007/how-to-edit-collection-fields-zf2-on-the-fly-with-jquery
*/

/**
* Parametros:
* ===========
* objParamsJson:
* Objeto en formato Json con los parametros para el constructor con los siguientes campos:
* - selectorBase: Selector del Elemento Base de la Coleccion.
* - btnAdd: Selector para el Boton de Agregar elementos a la colección.
* - btnDel: Selector para el Boton de Eliminar elementos a la colección.
* - callbackPostAdd: (opcional) Funcion Callback que se ejecutará luego de Agregar elementos a la colección.
* - callbackPostDel: (opcional) Funcion Callback que se ejecutará luego de Eliminar elementos a la colección.
* - minElementosCollection (opcional) Minimo Nro de elementos que puede tener la colección.
* - callbackBeforeAdd: (opcional) Funcion Callback que se ejecutará antes de Agregar elementos a la colección.  
* - callbackBeforeDel: (opcional) Funcion Callback que se ejecutará antes de Eliminar elementos a la colección.
* -                    Los Callback before deben retornar false si se desea detener la ejecucion del evento.
*
* Ejemplo de Uso:
*================
*     var form = new FormCollectionDynamic({ 
*                      selectorBase: '#tab1 > div', 
*                      btnAdd: '#btn-add',
*                      btnDel: '.btn-danger',
*                      callbackPostAdd: addDetalle,
*                      callbackPostDel: actualizarTotales
*              });
*
*/

// Función Constructora
function FormCollectionDynamic(objParamsJson){
    var self=this;

    this.selectorBase=objParamsJson.selectorBase;
    this.selectorCollection=this.selectorBase+' > div';
    this.selectorSpan=this.selectorBase+' > span';    
    this.objParamsJson=objParamsJson;
    
    this.countElements=$(this.selectorCollection).length;
    this.template_base = $(this.selectorSpan).data('template');

    //Toma como el minimo de elementos que debe tener la Coleccion el nro de elementos inicial    
    if (this.objParamsJson.minElementosCollection=== undefined){
        this.minElementosCollection= 0;
    }   
    else this.minElementosCollection = objParamsJson.minElementosCollection;

    $(objParamsJson.btnAdd).on('click',function (event){ 
        event.preventDefault();
        self.addElement(event,self)
    });

    this.btnDel = this.selectorCollection+"> "+objParamsJson.btnDel;
    
    $(this.btnDel).on('click',function (event){ 
        event.preventDefault();
        self.removeElement(event,self);
    });
    
    console.log("Ejecutado Constructor de FormCollectionDynamic  -> count = "+this.countElements+"("+this.minElementosCollection+")");
}

//---------------------- Metodos de la Clase ------------------------

FormCollectionDynamic.prototype.addElement =function(event,form){
        var currentCount=form.countElements;
        if (form.objParamsJson.callbackBeforeAdd){
            var result = form.objParamsJson.callbackBeforeAdd(currentCount);
            if(!result) return false;
        }

        var template = form.template_base.replace(/__index__/g, currentCount);
        $(form.selectorBase).append(template);

        if (form.objParamsJson.callbackPostAdd){
            form.objParamsJson.callbackPostAdd(currentCount)
        }

        form.countElements++;

        $(form.btnDel).unbind('click');
        $(form.btnDel).on('click',function (event){ 
            event.preventDefault();
            form.removeElement(event,form);
        });

        return false;
    }


 FormCollectionDynamic.prototype.removeElement= function (event,form){
        var currentCount = $(form.selectorCollection).length;

        if (form.objParamsJson.callbackBeforeDel){
            var result = form.objParamsJson.callbackBeforeDel(currentCount);
            if(!result) return false;
        }

        var minimo=form.minElementosCollection;
        //console.log("currentCount: "+currentCount+" Minimo = "+minimo);
        if(currentCount>minimo){
            $(event.target).closest("div").remove();    

            if (this.objParamsJson.callbackPostDel){
                this.objParamsJson.callbackPostDel(currentCount)
            }
        }
        else alert("Debe haber por lo menos "+minimo+" elemento(s)!");
        return false;   
    }
