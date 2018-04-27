var CharsCounter = new function(){
    this.init = function(){
        $('[data-chars-count] input').on('keyup change', function(){
            count($(this).closest('[data-chars-count]'));
        });
        
        $('[data-inline-input]').each(function(){ placeholder(this); });
        
        $('[data-inline-input]').on('keyup keypress cut copy paste mouseup', function(){ 
            $(this).parent().find('input').val($(this).text());
            count($(this).closest('[data-chars-count]'));
            
            placeholder(this);
        });
        
        $('[data-inline-input]').on("paste", function(e) {
            
            // cancel paste
            e.preventDefault();
            
            // get text representation of clipboard
            var text = e.originalEvent.clipboardData.getData("text/plain");
            
            var limit = $(this).parent().attr('data-limit');
            if(text.length > limit){
                text = text.trim();
                text = text.substring(0, limit);
            }

            // insert text manually
            document.execCommand("insertHTML", false, text);
        });
        
        $('[data-inline-input]').on('keydown', function(event){ 
            var limit = $(this).parent().attr('data-limit');
            var len = $(this).parent().children('input').val().length;
            if(len >= limit && ![8,37,38,39,40,46].includes(event.keyCode)){
                event.preventDefault();
            }
        });
    }
    
    function placeholder(element)
    {
        var len = $(element).parent().children('input').val().length;
        if(len > 0){
            $(element).parent().find('[data-placeholder]').css('opacity', 0);
        }else{
            $(element).parent().find('[data-placeholder]').css('opacity', 1);
        }
    }
    
    function count(element){
        var limit = $(element).attr('data-limit');
        var len = $(element).children('input').val().length;
        var $counter = $(element).children('[data-chars-counter]');

        var text = '';
        if(limit>0){
            text = len+'/'+limit;
        }else{
            text = len;
        }
        
        $counter.text(text);
        
        if(limit){
            if(len > 0.95*limit){
                $counter.removeClass('text-warning').addClass('text-danger');
            }else if(len > 0.75*limit){
                $counter.removeClass('text-danger').addClass('text-warning');
            }else{
                $counter.removeClass('text-warning text-danger');
            }
        }
    }
}