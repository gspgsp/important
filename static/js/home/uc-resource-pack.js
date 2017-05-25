/**
 * ns框架核心
 * @author Wangyiqun
 * @date 2014-03-15
 */
(function(){


    if(window.uc){
        return;
    }

    var uc = window.uc = {};

    uc.modules = {};

    uc.getWinHeight = function() {
        var de = document.documentElement;
        return self.innerHeight||(de && de.clientHeight)||document.body.clientHeight;
    }

    uc.getWinWidth = function() {
        var de = document.documentElement;
        return self.innerWidth||(de && de.clientWidth)||document.body.clientWidth;
    }

    uc.getY = function(id){
        var element = $(id)[0];
        var y = 0;
        for (var e = element; e; e = e.offsetParent) {
            y += e.offsetTop;
        }//此时y为包含scrollHeight
        for (e = element.parentNode; e && e != document.body; e = e.parentNode) {
            if (e.scrollTop)
                y -= e.scrollTop;
        }
        return y;//返回不包含scrollHeight的y
    };
    /**
     * 得到元素的可见页面的x坐标
     * @param {Object} id
     */
    uc.getX = function(id){
        var element = $(id)[0];
        var x = 0;
        for (var e = element; e; e = e.offsetParent) {
            x += e.offsetLeft;
        }//此时y为包含scrollWidth
        for (e = element.parentNode; e && e != document.body; e = e.parentNode) {
            if (e.scrollLeft)
                x -= e.scrollLeft;
        }
        return x;//返回包含scrollWidth的y
    };

    /**
     * 获取若干元素的总高度
     * @param ids
     */
    uc.getElementsHeight = function(ids){
        var height = 0 ;
        for(var i = 0 ; i < ids.length; i++){
            height += $("#" + ids[i]).height();
        }
        return height;
    }

    /**
     * 自适应高度，参数为调整需要自适应的函数,该函数可以接受窗口高度的参数
     */
    uc.autoFitHeight= function(fun){
        if($.isFunction(fun)){
            $(window).resize(function() {
                fun.call(this,uc.getWinHeight());
            });
        }
    }

    uc.showServerException = function(text){
        var dialog = new BUI.Overlay.Dialog({
            title:'服务器出现异常',
            width:500,
            height:350,
            mask:true,  //设置是否模态
            bodyContent:"<div style='width:470px;height:294px;overflow:auto;' >"+text+"</div>",
            closeAction:'destroy',
            buttons:[]
        });
        dialog.show();
    }

    uc.PromptToLoginPage = function(text){
        if(!text){
            text = "您未登录或者登录超时，请重新登录。";
        }
        var dialog = new BUI.Overlay.Dialog({
            title:'请求失败',
            width:300,
            height:130,
            mask:true,  //设置是否模态
            bodyContent:"<div  style='font-size:14px'>"+text+"</div>",
            closeAction:'destroy',
            buttons:[{
                text:'重新登录',
                elCls : 'button button-primary',
                handler : function(){
                    top.location.href = window.basePath+"/passport/login.do"
                }
            },{
                text:'取消',
                elCls : 'button button-primary',
                handler : function(){
                    this.close();
                }
            }]
        });
        dialog.show();
    }

    uc.showFormErrors = function(errorInfo , form){
        for(var fieldName in errorInfo){
            var field = form.getField(fieldName);
            if(field){
                field.showErrors([errorInfo[fieldName]]);
            }
        }
    }


    uc.buiAjaxErrorHandler = function(event , errorFun){
        uc.ajaxErrorHandler.apply(null ,[event.error.jqXHR , event.error.jqXHR.statusText , event.error.errorThrown , errorFun]);
    }

    /**
     * ajax请求异常处理
     * @param jqXHR
     * @param textStatus
     * @param errorThrown
     * @param errorFun
     */
    uc.ajaxErrorHandler = function(jqXHR, textStatus, errorThrown , errorFun , validErrorFun , form){
        //请求超时处理
        if(textStatus=="timeout"){
            BUI.Message.Alert('请求超时，请重新请求','warning');
        }
        else{//其他异常处理
            var errorMsg = "" , errorType = "";
            var unexpectedException = false;//非预期的异常标志
            if(jqXHR.readyState==4){//请求完成，并且后台发来异常错误信息
                var text = jqXHR.responseText;
                /**
                 * 异常代码401，含义：未登录
                 * 处理方式：
                 * 1.用户点击重新登录按钮，被系统带到登录界面
                 * 2.用户点击留在当前界面，则关闭弹出框，继续留在当前
                 */
                if(jqXHR.status==401){
                    uc.PromptToLoginPage(text);
                }
                /**
                 * 异常代码403，含义：访问指定功能时权限不足
                 * 处理方式：
                 * 告知用户没有权限做此操作
                 */
                else if(jqXHR.status==403){
                    errorMsg = "您没有权限做此操作！";
                    errorType = "warning";
                }
                /**
                 * 异常代码404，含义：未找到页面或功能
                 * 处理方式：
                 * 告知用户未找到页面或功能
                 */
                else if(jqXHR.status==404){
                    errorMsg = "未找到页面或功能！";
                    errorType = "warning";
                }
                else if(jqXHR.status==500){//服务器异常
                    //从html中截取有效地错误信息
                    if(text.indexOf("<html>")>=0){
                        var start = text.indexOf("<pre>"),
                            end = text.indexOf("</pre>");
                        text = text.substring(start,end+6);
                    }
                    uc.showServerException(text);
                }
                /**
                 * 校验异常
                 */
                else if(jqXHR.status==632){
                    errorMsg = text;
                    errorType = "warning";
                }
                /**
                 * 异常代码631，含义：出现业务异常
                 * 处理方式：
                 * 告知用户业务异常内容
                 */
                else if(jqXHR.status==631){
                    errorMsg = text;
                    errorType = "warning";
                }
                else
                    unexpectedException = true;
            }
            else{
                unexpectedException = true;
            }
            if(unexpectedException){//出现非预期异常，将异常状态值、状态码和状态消息提示出来
                var readyStateText = "";
                if(jqXHR.readyState==0){
                    readyStateText = "请求未初始化（服务可能已停止，请检查服务）";
                }
                else if(jqXHR.readyState==1){
                    readyStateText = "请求已经建立，但是还没有发送";
                }
                else if(jqXHR.readyState==2){
                    readyStateText = "请求已发送，正在处理中";
                }
                else if(jqXHR.readyState==3){
                    readyStateText = "请求在处理中,服务器还没有完成响应的生成";
                }
                else if(jqXHR.readyState==4){
                    readyStateText = "响应完成，已获取到服务器响应";
                }
                errorMsg = "状态值 :  "+readyStateText+"<br/>状态码  :  "+jqXHR.status+"<br/>异常内容 :  "+jqXHR.statusText;
                errorType = "error";

            }
        }

        /**
         * 校验错误
         */
        if(jqXHR.status === 632){

            var errorInfo = eval("(" + errorMsg + ")");
            /**
             * 如果设置了错误处理函数，则不在处理
             */
            if(validErrorFun && $.isFunction(validErrorFun)){

                validErrorFun.call(null , errorInfo);

            }
            // 如果没用错误处理函数，但是设置了form参数，则自动在form中显示错误信息
            else if(form){
                uc.showFormErrors(errorInfo , form);
            }
            // 未捕捉，则直接弹窗提示
            else{
                BUI.Message.Alert(errorMsg, 'error');
            }

        }else
        //执行程序员的错误处理函数
        if(errorFun && $.isFunction(errorFun)){
            errorFun.apply({},[jqXHR, textStatus, errorThrown ]);
        }else{
            BUI.Message.Alert(errorMsg, 'error');
        }

    }


    /**
     * @Title: 框架请求方法
     * @Description: 统一调用该方法做数据请求
     * @author nidongsheng
     * @date 2013-10-19
     *
     * @param config 具体属性请参照jQuery.ajax(settings)方法的settings属性 框架jquery版本为1.8.1
     */
    uc.ajax = function(config){
        if(!config.url){//当请求的url地址不存在时，抛出错误提示
            BUI.Message.Alert('请填写url地址','error');
            return;
        }

        //覆盖查询成功的回调函数
        //缓存用户定义的查询成功的回调函数
        var successFun = config.success,
            errorFun = config.error,
            validErrorFun = config.validError,
            form = config.form;

        /**
         * 1.data:回填数据
         * 2.textStatus:服务器返回的状态值
         * 3.jqXHR jquery 以xmlHTTPRequest为基础封装出来的对象
         */
        config.success = function(data,textStatus,jqXHR){
            //1.程序员回调函数
            successFun.apply({},[data,textStatus,jqXHR]);
        }
        /**
         * 1.jqXHR jquery 以xmlHTTPRequest为基础封装出来的对象
         * 2.textStatus:服务器返回的状态值
         * 3.errorThrown 异常对象
         */
        config.error = function(jqXHR, textStatus, errorThrown){
            uc.ajaxErrorHandler.apply(null , [jqXHR, textStatus, errorThrown,errorFun,validErrorFun , form]);
        }
        //当用户取消开启框架错误函数处理时，关闭框架错误函数
        if(config.callFrameError===false){
            config.error = errorFun;
        }
        //做数据请求
        config.cache = false;//取消请求缓存，修正ie系列浏览器下不更新数据的bug
        $.ajax(config);
    }



    uc.final = {};
    /**
     * 表格查询结果为空提示
     * @type {{}}
     */
    uc.final.EMPTY_DATA_TIP = "<div class='uc-empty-data-tip'>查询成功，没有符合条件的记录！</div>";

    /**
     * 简化bui获取组件的工具函数
     * @param id
     * @returns {Controller}
     */
    uc.getBuiCmp = function(id){
        return BUI.Component.Manager.getComponent(id);
    }


    /**
     * 成功提示
     * @param title
     * @param text
     * @returns {BUI.Overlay.Dialog}
     */
    uc.showSuccess = function(title , text){
        if(arguments.length == 1){
            text = title;
        }

        var wrap = $("<div class='bui-message bui-dialog bui-overlay bui-ext-position x-align-cc-cc' style='width:240;height:120;position: absolute;z-index: 10000;display: block'></div>").appendTo(document.body);
        var header = $('<div class="bui-stdmod-header"><div class="header-title">').appendTo(wrap);
        var body = $('<div class="bui-stdmod-body"><div class="x-icon x-icon-success"></div>').appendTo(wrap);
        var content = $('<div class="bui-message-content">' + text + '</div>').appendTo(body);
        var footer = $('<div class="bui-stdmod-footer"></div>').appendTo(wrap);


        wrap[0].style.left = (uc.getWinWidth() - 240)/2 + "px";
        wrap[0].style.top = (uc.getWinHeight() - 120)/2 + "px";
//        var dialog = new BUI.Overlay.Dialog({
//            title:title || "提示",
//            width:240,
//            height:120,
//            mask:false,  //设置是否模态
//            bodyContent:text,
//            closeAction:'destroy',
//            buttons:[]
//        });

        setTimeout(function(){
            $(wrap).remove();
        } , 1500);

    }


    /**
     * 创建异步请求的selector，并提供字段适配
     */
    uc.createAjaxSelector = function(config){
        var cfg = $.extend({
            url:"",
            value:-1,
            blankItem:true,
            dataConvertor:{
                textField: "name",
                valueField: "id"
            }
        },config, true);
        $.get(cfg.url, function (data) {
            cfg.dataConvertor.blankItem = cfg.blankItem;
            var items  = uc.listHelper.conver2ListNodes(data,cfg.dataConvertor);
            if (cfg.blankItem && items[0].value != "-1") {
                items.splice(0, 0, {text: '===请选择===', value: '-1'}); //从第0个位置开始插入
            }
            // 生产商选择
            cfg.items = items;
            var selector = new BUI.Select.Select(cfg);
            selector.render();
            if(cfg.value){
                selector.setSelectedValue(cfg.value);
            }
            if(cfg.callBack){
                cfg.callBack.call(null,selector);
            }
        });
    }


    $(function(){
        /*setInterval(function(){
            $(top.document.body).find("#tabContainer").resize();
            $(window).resize();
        } , 2000)*/
    })

})();
(function(){if(uc.Module){return}uc.modules={};uc.Class=function(){};uc.Class.extend=function(e){var f=function(){},g,d=this,b=e&&e.init?e.init:function(){d.apply(this,arguments)},c;f.prototype=d.prototype;c=b.fn=b.prototype=new f();for(g in e){if(typeof e[g]==="object"&&!(e[g] instanceof Array)&&e[g]!==null){c[g]=$.extend(true,{},f.prototype[g],e[g])}else{c[g]=e[g]}}c.constructor=b;b.extend=uc.Class.extend;return b};var a=window.uc;if(!a.Modules){uc.Modules={}}if(!a.ModuleClasses){uc.ModuleClasses={}}uc.Module=uc.M=uc.Class.extend({init:function(c){var d=["name","containerId"];var b=this;$(d).each(function(e,f){if(!(f in c)){alert("uc.Module创建失败，缺少必要参数："+f)}b[f]=c[f]});uc.Modules[this.name]=this;$(function(){b.ready.call(b)})},onUnLoad:function(){},unLoad:function(){this.onUnLoad()},$:function(b){if(!this.container){this.container=$("#"+this.containerId)}return this.container.find(b)},ready:function(){}});uc.Module.getModuleByName=function(b){return uc.Modules[b]};uc.Module.addClass=function(c,b){uc.ModuleClasses[c]=b};uc.Module.getClassByName=function(b){return uc.ModuleClasses[b]};uc.BaseModule=uc.M.extend({init:function(c){var b=this;uc.M.fn.init.call(this,c)},createCmps:function(){},bindEvents:function(){},initVars:function(){},ready:function(){uc.M.fn.ready.call(this);this.createCmps();this.initVars();this.bindEvents()}})})();
(function(){uc.treeHelper={};_converNode=function(e,d,a){var a=d.id;var c=[];for(var b=0;b<e.length;b++){var f=e[b];if(f.pid==a){c.push(f)}}if(c.length>0){d.children=c}for(var b=0;b<c.length;b++){_converNode(e,c[b],c[b].id)}};uc.treeHelper.convert2TreeNodes=function(e,c){var a=$.extend({pidField:"parentId",textField:"name"},c,true);if(!e||e.length<1){return e}var b=[];for(var d=0;d<e.length;d++){var f=e[d];if($.isFunction(c)){c.call(null,f)}else{f.text=f[a.textField];f.pid=f[a.pidField];f.expanded=true}}for(var d=0;d<e.length;d++){var f=e[d];if(!f.pid){b.push(f);_converNode(e,f,f.id)}}return b};uc.listHelper={};uc.listHelper.conver2ListNodes=function(d,b){var a=$.extend({blankItem:true,textField:"name",valueField:"id"},b,true);if($.isFunction(b)){for(var c=0;c<d.length;c++){var e=d[c];b.call(null,e)}if(a.blankItem){d.unshift({text:"===请选择===",value:-1})}return d}for(var c=0;c<d.length;c++){var e=d[c];e.text=e[a.textField];e.value=e[a.valueField]}if(a.blankItem){d.unshift({text:"===请选择===",value:-1})}return d};uc.Regs={CONTAINS_CHINESE:/[\u4E00-\u9FA5]/gi};uc.parameterHelper={encodeIfChinese:function(a){if(!a){return a}if(uc.Regs.CONTAINS_CHINESE.test(a)){return encodeURI(encodeURI(a))}return a},encode:function(d,a){if(!d||!a||!$.isArray(a)||a.length<1){return}for(var b=0;b<a.length;b++){var c=a[b];if(d[c]){d[c]=encodeURI(d[c])}}},doubleEncode:function(d,a){if(!d||!a||!$.isArray(a)||a.length<1){return}for(var b=0;b<a.length;b++){var c=a[b];if(d[c]){d[c]=encodeURI(encodeURI(d[c]))}}}}})();
(function(){if(uc.Enum){return}uc.Enum=function(d){this.data=d;for(var b=0;b<d.length;b++){var e=d[b];for(var a=0;a<e.length;a++){var c=e[a]}}};uc.Enum.prototype.getTextByOrdinal=function(a){if(a===null||a===undefined){return""}return this.data[a][0]};uc.Enum.prototype.getHtmlByOrdinal=function(a){if(a===null||a===undefined){return""}return this.data[a][1]};uc.Enums={};uc.Enums.genderEnum=new uc.Enum([["男","<label>男</label>"],["女","<label>女</label>"]]);uc.Enums.statusEnum=new uc.Enum([["启用","<label style='color:green' >启用</label>"],["禁用","<label style='color:red'>禁用</label>"],["未启用","<label style='color:blue'>未启用</label>"]]);uc.Enums.userStatusEnum=new uc.Enum([["启用","<label style='color:green'>启用</label>"],["禁用","<label style='color:red'>禁用</label>"]])})();
(function(){var a=window.uc;a.Dict=function(e){this.data=e;for(var c=0;c<e.length;c++){var f=e[c];for(var b=0;b<f.length;b++){var d=f[b]}}};a.Dict.prototype.getTextByOrdinal=function(b){return this.data[b][0]};a.Dict.prototype.getHtmlByOrdinal=function(b){return this.data[b][1]};a.Dicts={_dicts:null,_dictItems:null,dictsMap:{},initAllDicts:function(f,b){a.Dicts._dicts=f;a.Dicts._dictItems=b;for(var e=0;e<f.length;e++){var g=f[e];a.Dicts.dictsMap[g.id]=g;g.children=[];g._itemsValueMap={}}for(var d=0;d<b.length;d++){var c=b[d];var g=a.Dicts.dictsMap[c.dictId];if(!g){alert("加载字典失败，字典项对应的字典："+c.dictId+"不存在!");return}c.text=c.name;g.children.push(c);g._itemsValueMap[c.value]=c}console.log("dictsMap:",a.Dicts.dictsMap)},getDictById:function(b){return top.uc.Dicts.dictsMap[b]},getItemsOfDict:function(b){var f=top.uc.Dicts.dictsMap[b];if(!f){alert("字典："+b+"不存在，获取字典项失败!");return}var d=[];for(var c=0;c<f.children.length;c++){var e=f.children[c];d.push(e)}return d},getItemByValueOfDict:function(b,c){var d=top.uc.Dicts.getDictById(b);if(!d){alert("获取字典项失败：指定的字典"+b+"不存在！");return}return d._itemsValueMap[c]},getItemAttrByValueOfDict:function(b,e,c){var d=top.uc.Dicts.getItemByValueOfDict(b,e);if(!d){return null}return d[c]},getItemText:function(b,c){return top.uc.Dicts.getItemAttrByValueOfDict(b,c,"text")},createSelectorOfDict:function(f,e){var c=$.extend({blankItem:true,value:-1},e,true);var d=a.Dicts.getItemsOfDict(f);c.items=d;if(c.blankItem&&d[0].value!="-1"){d.splice(0,0,{text:"===请选择===",value:"-1"})}var b=new BUI.Select.Select(c);b.render();if(c.value){b.setSelectedValue(c.value)}return b}}})();
(function(a){uc.SliderPlayer=function(b){this.options=$.extend(true,{wrap:null,size:{width:500},elementSelector:".element",autoPlay:true,onToggle:null,direction:"left",playTimes:5000,speed:500},b);if(!this.options.wrap){console.log(uc.SliderPlayer.name+"参数错误！");return}this.$wrap=$(this.options.wrap);this.init()};uc.SliderPlayer.name="uc.SliderPlayer";uc.SliderPlayer.prototype={init:function(){var b=this;b.index=0;b.interval=0;b.$selectedLocation=null;this.$wrap.addClass("uc-slider-player-wrap ").height(this.options.size.height).width(this.options.size.width);this.$content=$("<div class='content'></div>").appendTo(this.$wrap);this.$elements=this.$wrap.find(this.options.elementSelector);this.elementNum=this.$elements.size();this.$elements.each(function(e,f){$(f).appendTo(b.$content);$(f).addClass("element")});if(this.options.direction=="left"){b.$content.height(b.options.size.height);b.$content.width(b.options.size.width*b.elementNum+1)}else{if(this.options.direction=="top"){}else{console.log(uc.SliderPlayer.name+"参数错误，播放方向不对")}}b.$locationsWrap=$("<div class='locations-wrap inline-block'></div>").appendTo(this.$wrap);if(b.elementNum==1){var c=$("").appendTo(b.$locationsWrap);c.click(function(e){return function(){b.toggle(e)}}(0))}else{for(var d=0;d<b.elementNum;d++){var c=$("<a href='javascript:void(0)' class='location inline-block'>"+(d+1)+"</a>").appendTo(b.$locationsWrap);c.click(function(e){return function(){b.toggle(e)}}(d))}}if(this.elementNum<2){return}b.toggle(0);if(b.options.autoPlay){b.interval=setInterval(function(){b.toggle((b.index+1)%b.elementNum)},b.options.playTimes)}},toggle:function(e){var c=this;c.$locationsWrap.find(".location:nth-child("+(c.index+1)+")").removeClass("selected");c.index=e;c.$locationsWrap.find(".location:nth-child("+(e+1)+")").addClass("selected");$(this.$content).stop().animate({left:-e*c.options.size.width},c.options.speed,"swing");if(c.options.onToggle){var h=$(c.$elements[e]);var b={};var f=h.attr("attrs");if(f&&typeof f=="string"){var g=f.split(";");for(var d=0;d<g.length;d++){b[g[d].split(":")[0]]=g[d].split(":")[1]}}c.options.onToggle.call(c,h,b)}}};uc.ksSwipeRobot=function(b){this.options=$.extend(true,{wrap:null,size:{width:280,height:220,swipeWidth:800},elementSelector:".section",marginRight:20,speed:100},b);if(!this.options.wrap){console.log("播放参数错误！");return}this.$wrap=$(this.options.wrap);this.init()};uc.ksSwipeRobot.prototype={init:function(){var b=this;this.intervalEvent=0;this.wrapWidth=this.$wrap.width();this.$wrap.height(this.options.size.height).width(this.options.size.swipeWidth).css({overflow:"hidden",position:"relative"});this.$content=$("<div style='position: absolute;left:0;top:0;overflow: visible'></div>").appendTo(this.$wrap);this.$elements=this.$wrap.find(this.options.elementSelector).css({height:this.options.size.height+"px",width:this.options.size.width+"px","margin-right":this.options.marginRight+"px","float":"left"});this.eleLength=this.$elements.length;this.$elements.each(function(c,d){$(d).appendTo(b.$content)});this.contentWidth=this.eleLength*(this.options.size.width+this.options.marginRight);if(this.contentWidth<this.wrapWidth){console.log("noSwipe,不滑动");return}this.$content.css("width",this.contentWidth*2+"px");this.$elements.clone().appendTo(b.$content);this.$wrap.mouseenter(function(){clearInterval(b.intervalEvent)}).mouseleave(function(){b.move()});this.move()},move:function(){var b=this;var c;clearInterval(b.intervalEvent);b.intervalEvent=setInterval(function(){c=parseInt(b.$content.css("left"));if(-c<b.contentWidth){b.$content.css("left",c-1+"px")}else{b.$content.css("left",0+"px")}},b.options.speed)}};uc.manySwipeRobot=function(b){this.options=$.extend(true,{wrap:null,size:{width:280,height:220,borderWidth:0},elementSelector:".section",autoPlay:true,playNum:3,marginRight:20,speed:1000,pauseTime:6000},b);if(!this.options.wrap){console.log("播放参数错误！");return}this.$wrap=$(this.options.wrap);this.init()};uc.manySwipeRobot.prototype={init:function(){var b=this;this.newBegin=false;this.toEvent=null;this.intervalEvent=0;this.isMoving=false;this.elementWidth=(this.options.size.width+(2*this.options.size.borderWidth)+this.options.marginRight);this.swipeWidth=this.options.playNum*this.elementWidth;this.wrapWidth=this.swipeWidth-this.options.marginRight;this.$wrap.height(this.options.size.height+(2*this.options.size.borderWidth)).width(this.wrapWidth).css({overflow:"hidden",position:"relative"});this.$content=$("<div style='position: absolute;left:0;top:0;overflow: visible'></div>").appendTo(this.$wrap);this.$elements=this.$wrap.find(this.options.elementSelector).css({height:this.options.size.height+"px",width:this.options.size.width+"px","margin-right":this.options.marginRight+"px","float":"left"});this.eleLength=this.$elements.length;this.$elements.each(function(d,e){$(e).appendTo(b.$content).addClass("ele"+d)});this.contentWidth=this.eleLength*this.elementWidth;this.$content.css({width:this.contentWidth+"px","z-index":1});if(this.eleLength<=this.options.playNum){console.log("noSwipe,不滑动");this.$wrap.find("a").hide();return}if(this.eleLength%this.options.playNum==0){this.cloneCount=1;this.gbsNum=this.eleLength*2}else{this.gbsNum=this.GBS(this.options.playNum,this.eleLength);this.cloneCount=this.gbsNum/this.eleLength-1}this.swipeTimes=this.gbsNum/this.options.playNum-1;this.contentWidthAfter=this.gbsNum*this.elementWidth;this.newLeft=(this.eleLength-this.options.playNum)*this.elementWidth;this.newRight=this.contentWidthAfter-this.swipeWidth;for(var c=0;c<this.cloneCount;c++){this.$elements.clone().appendTo(b.$content)}this.$content.css({width:this.contentWidthAfter+"px"});this.$wrap.find("a.next").appendTo(b.$wrap).click(function(){if(b.isMoving==true){return}b.startMoveLeft()});this.$wrap.find("a.prev").appendTo(b.$wrap).click(function(){if(b.isMoving==true){return}b.startMoveRight()});b.isNotMoving();b.runIntervalLeft()},startMoveLeft:function(){var c=this;var d;var b;if(c.isMoving==true){return}clearTimeout(c.toEvent);clearInterval(c.intervalEvent);d=parseInt(c.$content.css("left"));b=d-c.swipeWidth;if(-d!=c.swipeWidth*c.swipeTimes){c.isMoving=true;c.$content.animate({left:b+"px"},c.options.speed,function(){c.isMoving=false;if(c.newBegin==true){c.$content.css("left",0+"px");c.newBegin=false}})}else{c.$content.css("left",-c.newLeft+"px");c.newBegin=true;c.startMoveLeft()}c.runIntervalLeft()},startMoveRight:function(){var c=this;var d;var b;if(c.isMoving==true){return}clearTimeout(c.toEvent);clearInterval(c.intervalEvent);d=parseInt(c.$content.css("left"));b=d+c.swipeWidth;if(-d!=0){c.isMoving=true;c.$content.animate({left:b+"px"},c.options.speed,function(){c.isMoving=false;if(c.newBegin==true){c.$content.css("left",-c.newRight+"px");c.newBegin=false}})}else{c.$content.css("left",-c.contentWidth+"px");c.newBegin=true;c.startMoveRight()}c.runIntervalLeft()},isNotMoving:function(){var b=this;b.toEvent=setTimeout(function(){b.isMoving=false},b.options.pauseTime-100)},runIntervalLeft:function(){var b=this;clearTimeout(b.toEvent);clearInterval(b.intervalEvent);b.intervalEvent=setInterval(function(){b.startMoveLeft()},b.options.pauseTime)},GBS:function(d,e){for(var b=1;b<=d*e;b++){var c=d*b;if(c%e==0){return c}}}}})(window);
(function(){var a=window.uc;var b=a.PagingBar=function(d){var c=this;this.cfg=$.extend({id:null,async:false,asyncUrl:null,asyncWrap:null,layer:false,layerText:"加载中...",onPrev:null,onNext:null,onFirst:null,onLast:null,limit:30,onPaging:null,onComplete:function(e){}},d);this.isNull=function(e){return(e==undefined||e==null||e==="")};this.init=function(){this.$wrap=$("#"+this.cfg.id);this.$firstItem=this.$wrap.find(".paging-item.first");this.$prevItem=this.$wrap.find(".paging-item.prev");this.$nextItem=this.$wrap.find(".paging-item.next");this.$lastItem=this.$wrap.find(".paging-item.last");this.$currPageLabel=this.$wrap.find("#currPageIndex");this.$totalPage=this.$wrap.find("#totalPage");this.$startInput=this.$wrap.find("#start");this.$limitInput=this.$wrap.find("#limit");this.cfg.limit=this.$limitInput.val()-0;this.$limitInput.val(this.cfg.limit);this.$results=this.$wrap.find("#results");this.currPageIndex=this.$currPageLabel.html();var e=this.$totalPage.html();this.lastPage=parseInt(e>0?e:0);this.$pagingSubs=this.$wrap.find(".paging-sub");this.$goNum=this.$wrap.find("#goNum");this.$goNum.val((parseInt(this.currPageIndex)+1)>this.lastPage?this.lastPage:(parseInt(this.currPageIndex)+1));this.$goNumSubmit=this.$wrap.find("#goNumSubmit");this.$pagingSubs.bind("click",function(){c.$startInput.val(($(this).html()-1)*c.cfg.limit);if(c.cfg.onPaging&&$.isFunction(c.cfg.onPaging)){c.cfg.onPaging.call(c)}else{if(c.cfg.async===true){c.load(this)}}});this.$goNumSubmit.bind("click",function(){if(c.$goNum.val().match(/^\d.*$/)&&c.$goNum.val()>0&&c.$goNum.val()<=c.lastPage){c.$startInput.val((c.$goNum.val()-1)*c.cfg.limit);if(c.cfg.onPaging&&$.isFunction(c.cfg.onPaging)){c.cfg.onPaging.call(c)}else{if(c.cfg.async===true){c.load(this)}}}});this.$firstItem.bind("click",function(){if($(this).hasClass("enabled")){c.$startInput.val(0);if(c.cfg.onPaging&&$.isFunction(c.cfg.onPaging)){c.cfg.onPaging.call(c)}else{if(c.cfg.async===true){c.load(this)}}}});this.$prevItem.bind("click",function(){if($(this).hasClass("enabled")){c.$startInput.val(c.$startInput.val()-c.cfg.limit);if(c.cfg.onPaging&&$.isFunction(c.cfg.onPaging)){c.cfg.onPaging.call(c)}else{if(c.cfg.async===true){c.load(this)}}}});this.$nextItem.bind("click",function(){if($(this).hasClass("enabled")){c.$startInput.val((c.$startInput.val()-0)+c.cfg.limit);if(c.cfg.onPaging&&$.isFunction(c.cfg.onPaging)){c.cfg.onPaging.call(c)}else{if(c.cfg.async===true){c.load(this)}}}});this.$lastItem.bind("click",function(){if($(this).hasClass("enabled")){c.$startInput.val((c.$totalPage.html()-1)*c.cfg.limit);if(c.cfg.onPaging&&$.isFunction(c.cfg.onPaging)){c.cfg.onPaging.call(c)}else{if(c.cfg.async===true){c.load(this)}}}})};this.load=function(f){var g=this;var i=g.isNull(f)?$("form"):$(f).closest("form");console.log(i);if(i[0]&&g.cfg.asyncWrap){var h=this.cfg.asyncUrl;if(!h){h=i.attr("action")}var k=i.serialize();try{if(true===g.cfg.layer){g.loadLayer=layer.load(g.cfg.layerText,0)}$(g.cfg.asyncWrap).load(encodeURI(h),k,function(){if(true===g.cfg.layer){layer.close(g.loadLayer)}g.init();g.cfg.onComplete(g)})}catch(j){if(true===g.cfg.layer){layer.close(g.loadLayer)}}}};this.init()};b.prototype.setStartSize=function(c){return this.$startInput.val(c)}})();
