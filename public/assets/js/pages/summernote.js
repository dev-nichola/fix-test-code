$("#summernote").summernote({tabsize:2,height:120}),$("#hint").summernote({height:100,toolbar:!1,placeholder:"type with apple, orange, watermelon and lemon",hint:{words:["apple","orange","watermelon","lemon"],match:/\b(\w{1,})$/,search:function(e,n){n($.grep(this.words,(function(n){return 0===n.indexOf(e)})))}}});
