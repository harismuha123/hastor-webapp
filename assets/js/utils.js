var Utils = {
  clear_cache: function(){
    $.each(window.localStorage, function(key, value){
      window.localStorage.removeItem(key);
    })
  },
  remove_from_localstorage : function (key){
    window.localStorage.removeItem(key);
  },
  get_from_localstorage : function(key){
    return JSON.parse(window.localStorage.getItem(key));
  },
  set_to_localstorage : function(key, value){
    window.localStorage.setItem(key,JSON.stringify(value));
  },

  get_query_param : function( name ){
    var regexS = "[\\?&]"+name+"=([^&#]*)",
    regex = new RegExp( regexS ),
    results = regex.exec( window.location.search );
    if( results == null ){
      return "";
    } else{
      return decodeURIComponent(results[1].replace(/\+/g, " "));
    }
  }
}
