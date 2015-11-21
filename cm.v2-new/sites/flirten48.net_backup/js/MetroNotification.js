// Metro Notification V 3


jQuery(document).ready(function() {  

        // Plugins placing
        jQuery("body").append("<div id='divSmallBoxes'></div>");
        jQuery("body").append("<div id='divMiniIcons'></div><div id='divbigBoxes'></div>");

        jQuery(".OpenSideBar").pageslide({ direction: "left" });

    });


//Closing Rutine for Loadings
function MetroUnLoading() 
{

    jQuery(".LoadingBoxContainer").addClass("animated fadeOut fast");
    MetroMSGboxCount = MetroMSGboxCount -1;
    
    if(MetroMSGboxCount==0)
    {
        jQuery("#MsgBoxBack").removeClass("fadeIn").addClass("fadeOut").delay(300).queue(function()
        {
            ExistMsg = 0;
            jQuery(this).remove();
        });
    } 

    Point = 1;
    MetroLoadingTimer = 0;
    PointText = "";
    
}


// Messagebox
var ExistMsg = 0;
var MetroMSGboxCount = 0;
var PrevTop =  0;

(function ($) 
{
    $.MetroMessageBox = function (settings,callback) 
    {
        var MetroMSG, Content;
        settings = $.extend({
            title: "",
            content: "",
            NormalButton: undefined,
            ActiveButton: undefined,
            buttons: undefined,
            input: undefined,
            placeholder: "",
            options: undefined
        }, settings);

        // <div class="divMessageBox animated fadeIn fast">
        //     <div class="MessageBoxContainer">
        //         <div class="MessageBoxMiddle">
        //             <h2>Hola Mundo</h2>
        //             <p class="pText">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        //             tempor incididunt ut labore et dolore magna aliqua. </p>
        //             <input type='text' id='' placeholder='Hola Mundo'/><br/><br/>
        //             <div class="MessageBoxButtonSection">
        //                 <button>Aceptar</button>
        //                 <button>Cancelar</button>
        //             </div>
        //         </div>
        //     </div>
        // </div>
        var PlaySound = 0;

        PlaySound =1;
        //Messagebox Sound
        var audioElement = document.createElement('audio');
        audioElement.setAttribute('src', 'static/sound/messagebox.mp3');
        $.get();
        audioElement.addEventListener("load", function() {
        audioElement.play();
        }, true);


        audioElement.pause();
        audioElement.play();

        MetroMSGboxCount = MetroMSGboxCount + 1;
        

        if(ExistMsg==0)
        {
            ExistMsg = 1;
            MetroMSG = "<div class='divMessageBox animated fadeIn fast' id='MsgBoxBack'></div>";
            $("body").append(MetroMSG);
        }

        var InputType="";
        var HasInput = 0;
        if(settings.input != undefined)
        {
            HasInput = 1;
            settings.input = settings.input.toLowerCase();

            switch(settings.input)
            {
            case "text":
              InputType = "<input type='"+ settings.input +"' id='txt"+ MetroMSGboxCount +"' placeholder='"+ settings.placeholder +"'/><br/><br/>";
              break;
            case "password":
              InputType = "<input type='"+ settings.input +"' id='txt"+ MetroMSGboxCount +"' placeholder='"+ settings.placeholder +"'/><br/><br/>";
              break;

            case "select":
                if(settings.options == undefined)
                {
                    alert("For this type of input, the options parameter is required.");
                }
                else
                {
                    InputType  = "<select id='txt"+ MetroMSGboxCount+"'>";
                    for (var i = 0; i <= settings.options.length-1; i++) 
                    {
                        if(settings.options[i]=="[")
                        {
                            Name = "";
                        }
                        else
                        {
                            if(settings.options[i]=="]")
                            {
                                NumBottons = NumBottons + 1;
                                Name = "<option>"+ Name +"</option>";
                                InputType += Name;
                            }
                            else
                            {
                                Name += settings.options[i];
                            }
                        }
                    };
                    InputType += "</select>"
                }

             break;
            default:
              alert("That type of input is not handled yet");
            }

            
        }


        Content  = "<div class='MessageBoxContainer animated fadeIn fast' id='Msg"+ MetroMSGboxCount +"'>";
        Content += "<div class='MessageBoxMiddle'>";
        Content += "<span class='MsgTitle'>"+ settings.title +"</span class='MsgTitle'>";
        Content += "<p class='pText'>" + settings.content + "</p>";
        Content += InputType;
        Content += "<div class='MessageBoxButtonSection'>";

        if(settings.buttons == undefined)
        {
            settings.buttons = "[Accept]";
        }

        settings.buttons =  $.trim(settings.buttons);
        settings.buttons = settings.buttons.split('');
        
        var Name = "";
        var NumBottons = 0;
        if(settings.NormalButton == undefined)
        {
            settings.NormalButton = "#232323";   
        }

        if(settings.ActiveButton == undefined)
        {
            settings.ActiveButton = "#ed145b";   
        }


        for (var i = 0; i <= settings.buttons.length-1; i++) 
        {

            if(settings.buttons[i]=="[")
            {
                Name = "";
            }
            else
            {
                if(settings.buttons[i]=="]")
                {
                    NumBottons = NumBottons + 1;
                    Name = "<button id='bot"+ NumBottons +"-Msg"+ MetroMSGboxCount +"' class='botTempo' style='background-color: "+ settings.NormalButton +";'> " + Name + "</button>";
                    Content += Name;
                }
                else
                {
                    Name += settings.buttons[i];
                }
            }
        };

        Content += "</div>"; //MessageBoxButtonSection
        Content += "</div>"; //MessageBoxMiddle
        Content += "</div>"; //MessageBoxContainer

        // alert(MetroMSGboxCount);
        if(MetroMSGboxCount > 1)
        {
            $(".MessageBoxContainer").hide();
            $(".MessageBoxContainer").css("z-index", 99999);
        }

        $(".divMessageBox").append(Content);

        // Focus
        if(HasInput==1)
        {
            $("#txt"+MetroMSGboxCount).focus();
        }


        $('.botTempo').hover(function()
        {
            var ThisID = $(this).attr('id');
            // alert(ThisID);
            $("#"+ThisID).css("background-color", settings.ActiveButton);
        },function(){
            var ThisID = $(this).attr('id');
            $("#"+ThisID).css("background-color", settings.NormalButton);
        });

        // Callback and button Pressed
        $(".botTempo").click(function()
        {
            // Closing Method
            var ThisID = $(this).attr('id');
            var MsgBoxID  = ThisID.substr(ThisID.indexOf("-")+1);
            var Press = $.trim($(this).text());

            if(HasInput==1)
            {
                if (typeof callback == "function") 
                {   
                    var IDNumber = MsgBoxID.replace("Msg","");
                    var Value = $("#txt"+IDNumber).val();
                    if(callback) callback(Press, Value);
                }
            }
            else
            {
                if (typeof callback == "function") 
                {   
                    if(callback) callback(Press);
                }
            }

            $("#"+MsgBoxID).addClass("animated fadeOut fast");
            MetroMSGboxCount = MetroMSGboxCount -1;

            if(MetroMSGboxCount==0)
            {
                $("#MsgBoxBack").removeClass("fadeIn").addClass("fadeOut").delay(300).queue(function()
                {
                    ExistMsg = 0;
                    $(this).remove();
                });
            }
        });
            
    }

})(jQuery);

// Loading Screen
var Point = 1;
var MetroLoadingTimer = 0;
var PointText = "";
var MetroExist = false;

(function ($) 
{
    $.MetroLoading = function (settings,callback) 
    {
        var MetroMSG;

        settings = $.extend({
            title: undefined,
            content: undefined,
            img: undefined,
            timeout: undefined,
            special: undefined
        }, settings);

        MetroMSGboxCount = MetroMSGboxCount + 1;

        if(ExistMsg==0)
        {
            ExistMsg = 1;
            MetroMSG = "<div class='divMessageBox animated fadeIn fast' id='MsgBoxBack'></div>";
            $("body").append(MetroMSG);
        }

        if(settings.title == undefined)
        {
            settings.title = "Loading";
        }

        if(settings.content == undefined)
        {
            settings.content = "Please wait.";
        }

        var NoImage="";
        if(settings.img == undefined)
        {
            settings.img = "";
            NoImage = "<br/><br/><br/><br/><br/>"; 
        }
        else
        {
            settings.img = "<img src='"+ settings.img +"' class='animated fadeIn'/>";
        }


        var BodyLoading ="";
        BodyLoading += "<div class='LoadingBoxContainer'>";
        BodyLoading += "<div class='LoadingBoxMiddle'>";
        BodyLoading += "<div align='center'>";
        BodyLoading += "<br/><br/>"; 
        BodyLoading += settings.img;
        BodyLoading += "<br/><br/><br/>"; 

        if(settings.special == true)
        {
            // Special Loading type
            BodyLoading += "<span class='MsgTitle animated fadeIn' id='lblSpecialTitle'>"+ settings.title +"</span>";
        }
        else
        {
            // Normal loading
            BodyLoading += "<br/><span class='MsgTitle animated fadeIn'>"+ settings.title +"<span id='LoadingPoints'>.</span></span>";
            BodyLoading += "<p class='pText animated fadeIn'>"+ settings.content +"</p>";

        }

        BodyLoading += NoImage;
        BodyLoading += "</div>";
        BodyLoading += "</div>";
        BodyLoading += "</div>";

        
        
        

        $(".divMessageBox").append(BodyLoading);

        if(settings.timeout == undefined)
        {
            settings.timeout = 3000;
        }

        // Pointing Animation

        // if (typeof PointAnimation =='undefined') {
        //     alert("No Existe");
        // };




        var PointAnimation = setInterval(function()
        {



            if ($(".LoadingBoxContainer").length > 0)
            {
                // If loading exists
            }
            else
            {
                // If loading do not exist.
                window.clearInterval(PointAnimation);
            }

            if(settings.special == true)
            {
                // Special Loading
                if(MetroLoadingTimer==2)
                {
                    $("#lblSpecialTitle").removeClass("fadeIn").addClass("fadeOut").delay(300).queue(function()
                    {
                        $(this).text(settings.content);
                        $(this).clearQueue();
                        $(this).removeClass("fadeOut");
                        $(this).addClass("fadeIn");

                    });

                    MetroLoadingTimer += 1;
                }
                else
                {
                    if(MetroLoadingTimer == 5)
                    {
                        $("#lblSpecialTitle").removeClass("fadeIn").addClass("fadeOut").delay(300).queue(function()
                        {
                            $(this).text(settings.title);
                            $(this).clearQueue();
                            $(this).removeClass("fadeOut");
                            $(this).addClass("fadeIn");

                        });
                        MetroLoadingTimer = 0;
                    }
                    else
                    {
                        MetroLoadingTimer += 1;    
                    }
                }
               

            }
            else
            {
                // Normal Loading
                if(Point==0)
                {
                    PointText = ".";
                    Point +=1;
                }else if(Point == 1)
                {
                    PointText = "..";
                    Point +=1;
                }else if(Point == 2)
                {
                    PointText = "...";
                    Point += 1;
                }else if(Point == 3)
                {
                    PointText = "....";
                    Point = 0;
                }
                $("#LoadingPoints").text(PointText);
            }


        },750);

        

        setTimeout(function() 
        {
            //Closing Rutine
            $(".LoadingBoxContainer").addClass("animated fadeOut fast");
            MetroMSGboxCount = MetroMSGboxCount -1;

            window.clearInterval(PointAnimation);

            if(MetroMSGboxCount==0)
            {
                $("#MsgBoxBack").removeClass("fadeIn").addClass("fadeOut").delay(300).queue(function()
                {
                    ExistMsg = 0;
                    $(this).remove();
                });
            }

            if (typeof callback == "function") 
            {   
                if(callback) callback();
            }
        }, settings.timeout);

    
    }
})(jQuery);


// BigBox
var BigBoxes = 0;

(function ($) 
{
    $.bigBox = function (settings,callback) 
    {
        var boxBig, content;
        settings = $.extend({
            title: "",
            content: "",
            img: undefined,
            number: undefined,
            color: undefined,
            timeout: undefined
        }, settings);

        // bigbox Sound
         var audioElement = document.createElement('audio');
        audioElement.setAttribute('src', 'static/sound/bigbox.mp3');
        // audioElement.setAttribute('autoplay', 'autoplay');
        //audioElement.load()
        $.get();
        audioElement.addEventListener("load", function() {
        audioElement.play();
        }, true);

        

        audioElement.pause();
        audioElement.play();


        
        // <div class="bigBox animated fadeInUp">
        // <span>Hola Mundo</span>
        // <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        // tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        // quis nostrud exercitation ullamco. </p>
        // <div class="bigboxicon">
        //     <img src="static/img/cloud.png">
        // </div>
        // <div class="bigboxnumber">
        //     3
        // </div>
        // </div>
        BigBoxes = BigBoxes + 1;
        
        boxBig =  "<div id='bigBox"+BigBoxes+"' class='bigBox animated fadeIn fast'><div id='bigBoxColor"+ BigBoxes+"'><img class='botClose' id='botClose"+ BigBoxes +"' src='static/img/close.png'>";
        boxBig +=  "<span>"+ settings.title +"</span>";
        boxBig +=  "<p>"+settings.content +"</p>";
        boxBig +=  "<div class='bigboxicon'>";

        if(settings.img == undefined)
        {
            settings.img = "static/img/cloud.png";
        }
        boxBig +=  "<img src='"+ settings.img +"'>";
        boxBig +=  "</div>";

        boxBig +=  "<div class='bigboxnumber'>";
        if(settings.number != undefined)
        {
            boxBig +=  settings.number;
        }
        boxBig +=  "</div></div>";
        boxBig +=  "</div>";


        // stacking method
        $("#divbigBoxes").append(boxBig);
       

        if(settings.color == undefined)
        {
            settings.color = "#004d60";
        }
        $("#bigBox"+BigBoxes).css("background-color", settings.color );


        $("#divMiniIcons").append("<div id='miniIcon"+BigBoxes+"' class='cajita animated fadeIn' style='background-color: "+settings.color+";'><img src='"+ settings.img +"'/></div>");


        //Click Mini Icon
         $("#miniIcon"+BigBoxes).bind('click', function() 
         {
            var FrontBox = $(this).attr('id');
            var FrontBigBox = FrontBox.replace("miniIcon","bigBox");
            var FronBigBoxColor = FrontBox.replace("miniIcon","bigBoxColor");

            $(".cajita").each(function( index ) 
            {   
                var BackBox = $(this).attr('id');
                var BigBoxID = BackBox.replace("miniIcon","bigBox");
                
                $("#"+BigBoxID).css("z-index", 9998);
            });

            $("#"+FrontBigBox).css("z-index", 9999);
            $("#"+FronBigBoxColor).removeClass("animated fadeIn").delay(1).queue(function()
            {
                $(this).show();
                $(this).addClass("animated fadeIn");
                $(this).clearQueue();

            });
                
            
         });

         //Close Cross
         $("#botClose"+BigBoxes).bind('click', function() 
         {
            if (typeof callback == "function") 
            {   
                if(callback) callback();
            }

            var FrontBox = $(this).attr('id');
            var FrontBigBox = FrontBox.replace("botClose","bigBox");
            var miniIcon = FrontBox.replace("botClose","miniIcon");

            $("#"+FrontBigBox).removeClass("fadeIn fast");
            $("#"+FrontBigBox).addClass("fadeOut fast").delay(300).queue(function()
            {
                $(this).clearQueue();
                $(this).remove();
            });

            $("#"+miniIcon).removeClass("fadeIn fast");
            $("#"+miniIcon).addClass("fadeOut fast").delay(300).queue(function()
            {
                $(this).clearQueue();
                $(this).remove();
            });

            
         });

         if(settings.timeout != undefined)
        {
            var TimedID = BigBoxes;
            setTimeout(function() 
            {
                              
                $("#bigBox"+TimedID).removeClass("fadeIn fast");
                $("#bigBox"+TimedID).addClass("fadeOut fast").delay(300).queue(function()
                {
                    $(this).clearQueue();
                    $(this).remove();
                });

                $("#miniIcon"+TimedID).removeClass("fadeIn fast");
                $("#miniIcon"+TimedID).addClass("fadeOut fast").delay(300).queue(function()
                {
                    $(this).clearQueue();
                    $(this).remove();
                });

            }, settings.timeout); 
        }

    }
})(jQuery);

// .BigBox


// Small Notification
var SmallBoxes = 0;
var SmallCount = 0;
var SmallBoxesAnchos = 0;

(function ($) {
    $.smallBox = function (settings,callback) 
    {
        var BoxSmall, content;
        settings = $.extend({
            title: "",
            content: "",
            img: undefined,
            icon: undefined,
            color: undefined,
            timeout: undefined
        }, settings);


        // SmallBox Sound
         var audioElement = document.createElement('audio');
        audioElement.setAttribute('src', 'sounds/smallbox.mp3');
        // audioElement.setAttribute('autoplay', 'autoplay');
        //audioElement.load()
        $.get();
        audioElement.addEventListener("load", function() {
        audioElement.play();
        }, true);

        SmallBoxes = SmallBoxes + 1;

        BoxSmall = ""

        audioElement.pause();
        audioElement.play();

        // <div class="SmallBox animated fadeInRight fast">
        //     <div class="foto">
        //         <img src="static/img/pic1.png"> 
        //     </div>

        //     <div class="textoFoto">
        //         <span>Hola Mundo</span>
        //         <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor. lorem</span>
        //     </div>
        //     <div class='miniIcono'>
        //           <img class='miniPic' src='static/img/talk.png'>
        //     </div>
        // </div>
        var IconSection ="";
        var CurrentIDSmallbox = "smallbox"+SmallBoxes;

        if(settings.icon == undefined)
        {
            IconSection = "<div class='miniIcono'></div>";
        }
        else
        {
            IconSection = "<div class='miniIcono'><img class='miniPic' src='"+ settings.icon +"'></div>";
        }

        if(settings.img == undefined)
        {
            BoxSmall = "<div id='smallbox"+ SmallBoxes +"' class='SmallBox animated fadeInRight fast'><div class='textoFull'><span>"+ settings.title +"</span><p>"+ settings.content +"</p></div>"+ IconSection +"</div>";   
        }
        else
        {
            BoxSmall = "<div id='smallbox"+ SmallBoxes +"' class='SmallBox animated fadeInRight fast'><div class='foto'><img src='"+ settings.img +"'></div><div class='textoFoto'><br/><span>"+ settings.title +"</span><p>"+ settings.content +"</p></div>"+ IconSection +"</div>";
        }

        if(SmallBoxes == 1)
        {
            $("#divSmallBoxes").append(BoxSmall);
            SmallBoxesAnchos = $("#smallbox"+SmallBoxes).height() + 40;
        }
        else
        {
            var MetroExist = $(".SmallBox").size();
            if(MetroExist==0)
            {
                $("#divSmallBoxes").append(BoxSmall);
                SmallBoxesAnchos = $("#smallbox"+SmallBoxes).height() + 40;
            }
            else
            {
                $("#divSmallBoxes").append(BoxSmall);
                $("#smallbox"+SmallBoxes).css("top", SmallBoxesAnchos );
                SmallBoxesAnchos = SmallBoxesAnchos + $("#smallbox"+ SmallBoxes).height() + 20;
                
                $(".SmallBox").each(function( index ) 
                {   

                    if(index == 0)
                    {
                        $(this).css("top", 20 );
                        heightPrev = $(this).height() + 40;
                        SmallBoxesAnchos = $(this).height() + 40;
                    }
                    else
                    {
                        $(this).css("top", heightPrev );
                        heightPrev = heightPrev + $(this).height() + 20;
                        SmallBoxesAnchos = SmallBoxesAnchos + $(this).height() + 20;
                    }

                });
                
            }

            
        }

        // IE fix
        // if($.browser.msie)
        // {
        //     // alert($("#"+CurrentIDSmallbox).css("height"));
        // }



       /* if(settings.color == undefined)
        {
            $("#smallbox"+SmallBoxes).css("background-color", "#004d60" );
        }
        else
        {
            $("#smallbox"+SmallBoxes).css("background-color", settings.color );
        }*/

        if(settings.timeout != undefined)
        {

            setTimeout(function() 
            {
                var ThisHeight = $(this).height() + 20;
                var ID = CurrentIDSmallbox;
                var ThisTop = $("#"+CurrentIDSmallbox).css('top');

                SmallBoxesAnchos = SmallBoxesAnchos - ThisHeight;
                $("#"+CurrentIDSmallbox).remove();

                var Primero = 1;
                var heightPrev = 0;
                $(".SmallBox").each(function( index ) 
                {   

                    if(index == 0)
                    {
                        $(this).css("top", 20 );
                        heightPrev = $(this).height() + 40;
                        SmallBoxesAnchos = $(this).height() + 40;
                    }
                    else
                    {
                        $(this).css("top", heightPrev );
                        heightPrev = heightPrev + $(this).height() + 20;
                        SmallBoxesAnchos = SmallBoxesAnchos + $(this).height() + 20;
                    }

                });
                


            }, settings.timeout); 
        }
        
        // Click Closing
         $("#smallbox"+SmallBoxes).bind('click', function() 
         {
            if (typeof callback == "function") 
            {   
                if(callback) callback();
            }

            var ThisHeight = $(this).height() + 20;
            var ID = $(this).attr('id');
            var ThisTop = $(this).css('top');

            SmallBoxesAnchos = SmallBoxesAnchos - ThisHeight;
            $(this).remove();

            var Primero = 1;
            var heightPrev = 0;

            $(".SmallBox").each(function( index ) 
            {   

                if(index == 0)
                {
                    $(this).css("top", 20 );
                    heightPrev = $(this).height() + 40;
                    SmallBoxesAnchos = $(this).height() + 40;
                }
                else
                {
                    $(this).css("top", heightPrev );
                    heightPrev = heightPrev + $(this).height() + 20;
                    SmallBoxesAnchos = SmallBoxesAnchos + $(this).height() + 20;
                }

            });
            
         });
            
        
    }
})(jQuery);



// .Small Notification







function Hola()
{
    alert("Hola Mundo");
}


// Closing function for iPad and other tablets
function CloseSide()
{
    $.pageslide.close();
}


// Sounds



/*
 * jQuery pageSlide
 * Version 2.0
 * http://srobbin.com/jquery-pageslide/
 *
 * jQuery Javascript plugin which slides a webpage over to reveal an additional interaction pane.
 *
 * Copyright (c) 2011 Scott Robbin (srobbin.com)
 * Dual licensed under the MIT and GPL licenses.
*/

;(function($){
    // Convenience vars for accessing elements
    var $body, $pageslide;

    $(function(){
        $body = $('body'), $pageslide = $('#pageslide');

        // If the pageslide element doesn't MetroExist, create it
        if( $pageslide.length == 0 ) {
             $pageslide = $('<div />').attr( 'id', 'pageslide' )
                                      .css( 'display', 'none' )
                                      .appendTo( $('body') );
        }

        /* Events */
            
        // Don't let clicks to the pageslide close the window
        $pageslide.click(function(e) {
            e.stopPropagation();
        });
    
        // Close the pageslide if the document is clicked or the users presses the ESC key, unless the pageslide is modal
        $(document).bind('click keyup', function(e) {
            // If this is a keyup event, let's see if it's an ESC key
            if( e.type == "keyup" && e.keyCode != 27) return;
            
            // Make sure it's visible, and we're not modal      
            if( $pageslide.is( ':visible' ) && !$pageslide.data( 'modal' ) ) {          
                $.pageslide.close();
            }
        });
    });
    
    var _sliding = false,   // Mutex to assist closing only once
        _lastCaller;        // Used to keep track of last element to trigger pageslide

    /*
     * Private methods 
     */
    function _load( url, useIframe ) {
        // Are we loading an element from the page or a URL?
        if ( url.indexOf("#") === 0 ) {                
            // Load a page element                
            $(url).clone(true).appendTo( $pageslide.empty() ).show();
        } else {
            // Load a URL. Into an iframe?
            if( useIframe ) {
                var iframe = $("<iframe />").attr({
                                                src: url,
                                                frameborder: 0,
                                                hspace: 0
                                            })
                                            .css({
                                                width: "100%",
                                                height: "100%"
                                            });
                
                $pageslide.html( iframe );
            } else {
                $pageslide.load( url );
            }
            
            $pageslide.data( 'localEl', false );
            
        }
    }
    
    // Function that controls opening of the pageslide
    function _start( direction, speed ) {
        var slideWidth = $pageslide.outerWidth( true ),
            bodyAnimateIn = {},
            slideAnimateIn = {};
        
        // If the slide is open or opening, just ignore the call
        if( $pageslide.is(':visible') || _sliding ) return;         
        _sliding = true;
                                                                    
        switch( direction ) {
            case 'left':
                $pageslide.css({ left: 'auto', right: '-' + slideWidth + 'px' });
                bodyAnimateIn['margin-left'] = '-=' + slideWidth;
                slideAnimateIn['right'] = '+=' + slideWidth;
                break;
            default:
                $pageslide.css({ left: '-' + slideWidth + 'px', right: 'auto' });
                bodyAnimateIn['margin-left'] = '+=' + slideWidth;
                slideAnimateIn['left'] = '+=' + slideWidth;
                break;
        }
                    
        // Animate the slide, and attach this slide's settings to the element
        $body.animate(bodyAnimateIn, speed);
        $pageslide.show()
                  .animate(slideAnimateIn, speed, function() {
                      _sliding = false;
                  });
    }
      
    /*
     * Declaration 
     */
    $.fn.pageslide = function(options) {
        var $elements = this;
        
        // On click
        $elements.click( function(e) {
            var $self = $(this),
                settings = $.extend({ href: $self.attr('href') }, options);
            
            // Prevent the default behavior and stop propagation
            e.preventDefault();
            e.stopPropagation();
            
            if ( $pageslide.is(':visible') && $self[0] == _lastCaller ) {
                // If we clicked the same element twice, toggle closed
                $.pageslide.close();
            } else {                 
                // Open
                $.pageslide( settings );

                // Record the last element to trigger pageslide
                _lastCaller = $self[0];
            }       
        });                   
    };

    /*
     * Default settings 
     */
    $.fn.pageslide.defaults = {
        speed:      200,        // Accepts standard jQuery effects speeds (i.e. fast, normal or milliseconds)
        direction:  'right',    // Accepts 'left' or 'right'
        modal:      false,      // If set to true, you must explicitly close pageslide using $.pageslide.close();
        iframe:     true,       // By default, linked pages are loaded into an iframe. Set this to false if you don't want an iframe.
        href:       null        // Override the source of the content. Optional in most cases, but required when opening pageslide programmatically.
    };

    /*
     * Public methods 
     */

    // Open the pageslide
    $.pageslide = function( options ) {     
        // Extend the settings with those the user has provided
        var settings = $.extend({}, $.fn.pageslide.defaults, options);

        // Are we trying to open in different direction?
        if( $pageslide.is(':visible') && $pageslide.data( 'direction' ) != settings.direction) {
            $.pageslide.close(function(){
                _load( settings.href, settings.iframe );
                _start( settings.direction, settings.speed );
            });
        } else {                
            _load( settings.href, settings.iframe );
            if( $pageslide.is(':hidden') ) {
                _start( settings.direction, settings.speed );
            }
        }
        
        $pageslide.data( settings );
    }

    // Close the pageslide
    $.pageslide.close = function( callback ) {
        var $pageslide = $('#pageslide'),
            slideWidth = $pageslide.outerWidth( true ),
            speed = $pageslide.data( 'speed' ),
            bodyAnimateIn = {},
            slideAnimateIn = {}
                        
        // If the slide isn't open, just ignore the call
        if( $pageslide.is(':hidden') || _sliding ) return;          
        _sliding = true;
        
        switch( $pageslide.data( 'direction' ) ) {
            case 'left':
                bodyAnimateIn['margin-left'] = '+=' + slideWidth;
                slideAnimateIn['right'] = '-=' + slideWidth;
                break;
            default:
                bodyAnimateIn['margin-left'] = '-=' + slideWidth;
                slideAnimateIn['left'] = '-=' + slideWidth;
                break;
        }
        
        $pageslide.animate(slideAnimateIn, speed);
        $body.animate(bodyAnimateIn, speed, function() {
            $pageslide.hide();
            _sliding = false;
            if( typeof callback != 'undefined' ) callback();
        });
    }
})(jQuery);