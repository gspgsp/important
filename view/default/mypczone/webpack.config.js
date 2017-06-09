/* 引入操作路径模块和webpack */
var path = require('path');
var webpack = require('webpack');
var HtmlWebpackPlugin = require('html-webpack-plugin')
var ExtractTextPlugin = require("extract-text-webpack-plugin");

module.exports = {
	/* 输入文件 */
	entry:{
		'js/app': ["./src/assets/js/jquery-1.8.3.min.js","./src/assets/js/jquery.validate.min.js","./src/assets/js/common.js","./src/assets/js/other.js","./src/assets/js/tab.js"],
		'pages/index':'./src/views/index/index.js',
		'pages/headline':'./src/views/headline/headline.js',
		'pages/my':'./src/views/my/my.js',
		'pages/supplybuy':'./src/views/supplybuy/supplybuy.js',
		'pages/find':'./src/views/find/find.js',
		'pages/index_info':'./src/views/indexinfo/indexinfo.js',
		'pages/info_sale':'./src/views/infosale/infosale.js',
		'pages/info_buy':'./src/views/infobuy/infobuy.js',
		'pages/login':'./src/views/login/login.js',
		'pages/releasedetail':'./src/views/releasedetail/releasedetail.js',
		'pages/findpwd':'./src/views/findpwd/findpwd.js',
		'pages/register':'./src/views/register/register.js',
		'pages/agreement':'./src/views/agreement/agreement.js',
		'pages/headline':'./src/views/headline/headline.js',
		'pages/headline2':'./src/views/headline2/headline2.js',
		'pages/chself':'./src/views/chself/chself.js',
		'pages/creditIntro':'./src/views/creditIntro/creditIntro.js',
		'pages/chother':'./src/views/chother/chother.js',
		'pages/chother2':'./src/views/chother2/chother2.js',
		'pages/mySupply':'./src/views/mySupply/mySupply.js',
		'pages/myIntro':'./src/views/myIntro/myIntro.js',
		'pages/myComment':'./src/views/myComment/myComment.js',
		'pages/myMsg':'./src/views/myMsg/myMsg.js',
		'pages/mySudou':'./src/views/mySudou/mySudou.js',
		'pages/myHelp':'./src/views/myHelp/myHelp.js',
		'pages/myEdit':'./src/views/myEdit/myEdit.js',
		'pages/chargeDo':'./src/views/chargeDo/chargeDo.js',
		'pages/howCharge':'./src/views/howCharge/howCharge.js'
	},
	output: {
	    path: path.join(__dirname, '../../../../static/mypc'),
	    // 文件地址，使用绝对路径形式
	    filename: '[name].js',
	    //[name]这里是webpack提供的根据路口文件自动生成的名字
	    publicPath:'__MYPC__/'
	},
	plugins: [
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/index.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/index/index.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/index']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/headline.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/headline/headline.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/find.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/find/find.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/find']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/supplybuy.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/supplybuy/supplybuy.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/supplybuy']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/my.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/my/my.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/my']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/indexinfo.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/indexinfo/indexinfo.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/index_info']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/infosale.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/infosale/infosale.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/info_sale']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/infobuy.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/infobuy/infobuy.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/info_buy']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/login.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/login/login.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/login']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/releasedetail.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/releasedetail/releasedetail.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/releasedetail']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/findpwd.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/findpwd/findpwd.html'),
			hash:true,
			inject:true,
			chunks:['pages/findpwd']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/register.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/register/register.html'),
			hash:true,
			inject:true,
			chunks:['pages/register']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/agreement.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/agreement/agreement.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/agreement']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/headline.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/headline/headline.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/headline']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/headline2.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/headline2/headline2.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/headline2']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/creditIntro.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/creditIntro/creditIntro.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/creditIntro']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/chself.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/chself/chself.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/chself']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/chother.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/chother/chother.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/chother']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/chother2.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/chother2/chother2.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/chother2']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/mySupply.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/mySupply/mySupply.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/mySupply']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/myIntro.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/myIntro/myIntro.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/myIntro']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/myComment.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/myComment/myComment.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/myComment']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/myMsg.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/myMsg/myMsg.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/myMsg']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/mySudou.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/mySudou/mySudou.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/mySudou']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/myHelp.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/myHelp/myHelp.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/myHelp']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/myEdit.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/myEdit/myEdit.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/myEdit']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/chargeDo.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/chargeDo/chargeDo.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/chargeDo']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/howCharge.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/howCharge/howCharge.html'),
			hash:true,
			inject:true,
			chunks:['js/app','pages/howCharge']
		})
	],
	module: {
		rules: [
			// 解析.vue文件
			{
				test: /\.vue$/,
				loader: 'vue-loader'
			},
			// 转化ES6的语法
			{
				test: /\.js$/,
				loader: 'babel-loader',
				exclude: /node_modules/
			},
			// 编译css并自动添加css前缀
			{
				test: /\.css$/,
				loader: 'style-loader!css-loader'
				//loader: 'style!css'
			},
			// 图片转化，小于8K自动转化为base64的编码
			{
				test: /\.(png|jpg|gif|svg)$/,
				loader: 'url-loader',
				query: {
					limit: 200000,
					name: '[name].[ext]?[hash:7]'
				}
			}
		]
	},
	resolve: {
		// require时省略的扩展名，如：require('module') 不需要module.js
		extensions: ['.js', '.vue','.css'],
		// 别名，可以直接使用别名来代表设定的路径以及其他
		alias: {
			filter: path.join(__dirname, './src/filters'),
			components: path.join(__dirname, './src/components'),
			'vue': path.join(__dirname, './node_modules/vue/dist/vue.min.js')
		}
	}
}