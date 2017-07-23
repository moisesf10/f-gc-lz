var Application = (function(){
    "use strict";
    
    /**
     * Url utilizada pela aplicação
     */
    var ulr = null;
    /**
     * Url utilizada pela aplicação
     */
    var page = null;
    /**
     * Url utilizada pela aplicação
     */
    var params = new Array();
    
    function Application(){};
    /**
     * Construtor da classe
     * 
     */
    Application.prototype.init = function(){
             
        
            var uri = window.location;
            var uriString = uri.toString();
            var uriArray = uriString.split("/");
            var protocol = uriArray[0];
            var uri = uriArray[2];
            var page = uriArray[3];
            var params = new Array();
            for (var i in uriArray)
                if (i > 3)
                    params.push(uriArray[i]);
        
            this.url = protocol + '//' + uri;
            this.page = page;
            this.params = params;
    };
    
    /**
     * Método que retorna a URL utilizada na aplicação
     * 
     * @returns {String}
     */
    Application.prototype.getUrl = function(){
        return this.url;  
    };
        /**
     * Método que retorna o nome da página acessada
     * 
     * @returns {String}
     */
    Application.prototype.getPage = function(){
        return this.page;  
    };
    /**
     * Método que retorna os parâmetros da página acessada
     * 
     * @returns {Array}
     */
    
    Application.prototype.getParams = function(){
        return this.params;  
    };
    
     /**
     * Método verifica se o CPF passado como parâmetro é válido
     * 
     * @param {string} CPF Número do CPF
     * @returns {boolean}
     */
    Application.prototype.isValidCpf = function(cpf)
    {
       
       var blackList = ['11111111111','22222222222','33333333333','44444444444', '55555555555', '66666666666', '77777777777', '88888888888','99999999999','12345678909'];
           
        
        var exp = /\.|\-/g;
        cpf = cpf.replace( exp, "" ); 
        
        if (blackList.indexOf(cpf) >= 0)
            return false;
        
        var digitoDigitado = eval(cpf.charAt(9) +  cpf.charAt(10));
        var soma1=0, soma2=0;
        var vlr =11;
    
        for(var i=0;i<9;i++){
                soma1+=eval(cpf.charAt(i) * (vlr-1));
                soma2+=eval(cpf.charAt(i) * vlr);
                vlr--;
           
        }       
        
        
        soma1 = (((soma1*10)%11)==10 ? 0:((soma1*10)%11));
        soma2=(((soma2+(2*soma1))*10)%11);
        var digitoGerado=(soma1*10)+soma2;
        
        if(digitoGerado!=digitoDigitado)        
          return false;
		else 
		  return true;                 

    }
    
    /**
     * Método remove elemento HTML utilizando técnica de fade
     * 
     * @param {string} objectId Id do elemento a ser removido
     */
    Application.prototype.excludeRow = function(objectId)
    {

        objectId = '#'+objectId;
        $(objectId).fadeOut(300, function() { $(this).remove(); });
    }
    
     /**
     * Método faz upload de arquivo JS e anexa ao body
     * 
     * @param {string} url ULR do arquivo a ser baixado
     */
    Application.prototype.loadJs = function(url)
    {
        var s = document.createElement('script');
        s.src = url; // URL do seu script aqui
        document.head.appendChild(s);
    }
    
    
    
       /**
     * DEPRECATED
     * Método faz upload de arquivo JS e anexa ao HEAD
     * 
     * @param {string} url ULR do arquivo a ser baixado
     */
    Application.prototype.loadJs = function(url)
    {
        var s = document.createElement('script');
        s.src = url; // URL do seu script aqui
        document.head.appendChild(s);
    }
    
       /**
     * Método faz upload de arquivo CSS e anexa ao HEAD
     * 
     * @param {string} url ULR do arquivo a ser baixado
     */
    Application.prototype.loadCss = function(url)
    {
        var s = document.createElement('link');
        s.href = url; // URL do seu script aqui
        s.rel = "stylesheet";
        document.head.appendChild(s);
    }
    
    
      /**
     * Método faz upload de arquivo CSS e anexa ao HEAD
     * 
     * @param {string} url ULR do arquivo a ser baixado
     */
    Application.prototype.aguardeModal = function(acao)
    {
        var idElemento = 'modalAguarde';
        if (acao.toLowerCase() != 'abrir' && acao.toLowerCase() != 'fechar')
            return false;
        else
            if (acao.toLowerCase() == 'abrir')
            {
                if ($('#'+idElemento).length > 0)
		          return false;
                $("<div></div>")
	                   .attr('Id', idElemento).attr('Title', 'Aguarde').attr('class','modal').appendTo('body');
                var e = $('#' + idElemento);
                e.html('<div class="modal-content center-align"><p>Aguarde...</p><div class="row"><div class="col s8 offset-s2"><div class="progress"><div class="indeterminate"></div></div></div></div></div>');
                $('#' + idElemento).openModal({
                    dismissible: false,
                    opacity: .5
                });
            }else
            {
                if (acao.toLowerCase() === 'fechar')
                   if ($('#'+idElemento).length > 0)
                   {
                       $('#' + idElemento).closeModal();
                       $( "#"+idElemento ).remove();
                   }
            }

    }
    
    
     /**
     * Método faz upload de arquivo CSS e anexa ao HEAD
     * 
     * @param {string} url ULR do arquivo a ser baixado
     */
    Application.prototype.aguardeModalMessage = function(acao, message = '')
    {
        var idElemento = 'modalAguardeProgresso';
        if (acao.toLowerCase() != 'abrir' && acao.toLowerCase() != 'fechar' && acao.toLowerCase() != 'atualizar')
            return false;
        else
        {
            switch(acao.toLowerCase())
                {
                    case 'abrir':
                        if ($('#'+idElemento).length > 0)
                          return false;
                        $("<div></div>")
                               .attr('Id', idElemento).attr('Title', 'Aguarde').attr('class','modal').appendTo('body');
                        var e = $('#' + idElemento);
                        e.html('<div class="modal-content center-align"><p>Aguarde...</p><p class="modalAguardeProgresso"></p><div class="row"><div class="col s8 offset-s2"><div class="progress"><div class="indeterminate"></div></div></div></div></div>');
                        $('#' + idElemento).openModal({
                            dismissible: false,
                            opacity: .5
                        });
                    break;
                    case 'atualizar':
                        $('.modalAguardeProgresso label').html(message);
                    break;
                    case 'fechar':
                        if ($('#'+idElemento).length > 0)
                        {
                           $('#' + idElemento).closeModal();
                           $( "#"+idElemento ).remove();
                        }
                    break;
                }
            
        }

    }
    
    
    
    
    
    return Application;
}());

application = new Application();
application.init();





