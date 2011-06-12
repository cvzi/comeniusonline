/*
 * main.js
 *
 *       comeniusonline
 *
 *  comeniusonline is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  comeniusonline is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with comeniusonline; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 *  For questions contact
 *  cuzi@openmail.cc
 *
 * @copyright 2011 cuzi
 * @author cuzi <cuzi@openmail.cc>
 * @package comeniusonline
 * @version 1.06
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 */

function fadeError(el,del) {

    var classname = el.get('class');


    if(classname.contains('nofadeout')) {
        return;
    }

    var hide = function() {
        el.set('tween', {
            duration: '5000ms'
        });
        el.tween('opacity', '0');
        var del = function() {
            el.dispose();
        };
        del.delay(5000);
    };
    hide.delay(del>500?del:3000);
}

function printThisPage() {
    if(document.location.href.contains(baseurl)) {
      if(document.location.href.contains(baseurl+'?')) {
          document.location.href = document.location.href.replace(baseurl,baseurl+'print.php');
       }  else if(document.location.href.contains(baseurl+'index.php?')) {
          document.location.href = document.location.href.replace('index.php','print.php');
       } else {
           document.location.href = document.location.href.replace('/?','&').replace('?','&').replace(baseurl,baseurl+'print.php?q=');
       }
    }
}




var Tips2;


// OnLoad
window.addEvent('domready', function() {

    // login form
    if($('lpass')) {
        $('lpass').addEvent('keydown',function(event) {
            if (event.key == 'enter') {
                this.getParents('form')[0].submit();
            }
        });

    }


    // Fade out "hints"
    fadeError($$('.hint'));
    
    // Set predefined input descriptions
    if(!window.mobile) {

        if($$('.inputdec')[0]) {
            var inputdecs = $$('.inputdec');
            for(var i = 0,len = inputdecs.length; i < len;i++) {
                var id = inputdecs[i].get('id').replace('_dec','');
                if($(id)) {
                    $(id).set('class','inputWithDesc');
                    $(id).set('title',inputdecs[i].get('text')+' '+inputdecs[i].get('title'));

                    if('' == $(id).get('value')) {
                        $(id).store('color',$(id).getStyle('color'));
                        $(id).setStyle('color','Silver');
                        $(id).set('value',inputdecs[i].get('html'));
                        
                        // Delete name to prevent default values in database
                        $(id).store('name',$(id).get('name'));
                        $(id).set('name','');

                        $(id).addEvent('focus',function(inputdec) {
                            return function() {
                                if(inputdec.get('html') == this.get('value')) {
                                    this.set('value','');
                                    this.set('name',this.retrieve('name'));
                                    this.setStyle('color',this.retrieve('color'));
                                    if(this.get('name').indexOf('_password') != -1) { // Change to password if name contains "_password"
                                        this.set('name',this.get('name').replace('_password',''));
                                        this.set('type','password');
                                    }

                                }
                            };
                        }(inputdecs[i]));

                    }
                }
            }

            // Add tips
            var Tips1 = new Tips($$('.inputWithDesc'),{
                showDelay:10,
                hideDelay:500
            });
        }
    }

    // focus an special input
    if($$('.ffocus')[0]) {
        $$('.ffocus')[0].focus();

    }


    // Add tips to time acronyms
    var acronyms = $$('acronym');
    for(var i = 0,len = acronyms.length; i < len;i++) {
        var a = acronyms[i];
        if('time:' == a.get('title').substr(0,'time:'.length)) {
            var str = a.get('title').substr('time:'.length);
            a.set('title',str);
            a.set('class','timetip');
            var date = new Date();
            date.parse(a.get('text'));
            a.set('text',date.timeDiffInWords());
            a.store('date',date);

        }
    }
    Tips2 = new Tips($$('.timetip'));
    var updateTime = function() {
        var acronyms = $$('acronym');
        for(var i = 0,len = acronyms.length; i < len;i++) {
            var a = acronyms[i];
            if(a.retrieve('date')) {
                var date = a.retrieve('date');
                a.set('text',date.timeDiffInWords());
            }
        }
    };
    updateTime.periodical(1000*60);

    // Login password field on double click


    if($('slideoutlname')) {
        $('slideoutlname').addEvent('keydown',function(event) {
            if (event.key == 'enter') {
                $('slideoutlpass').focus();
            }
        });
    }

    if($('slideoutlpass')) {
        $('slideoutlpass').addEvent('keydown',function(event) {
            if (event.key == 'enter') {
                this.getParents('form')[0].submit();
            }

        });
    }

    if($('slideoutsubmit')) {
        $('slideoutsubmit').addEvent('keydown',function(event) {
            this.getParents('form')[0].submit();
        });
    }

    if($('slideout_button')) {
        $('slideout_button').addEvent('mouseover',function(event) {
            $('slideoutlname').focus();
        });
    }







});
