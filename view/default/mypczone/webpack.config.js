/* 引入操作路径模块和webpack */
var path = require('path');
var webpack = require('webpack');
var HtmlWebpackPlugin = require('html-webpack-plugin')

module.exports = {
	/* 输入文件 */
	entry:{
		'pages/index':'./src/views/index/index.js',
		'pages/my':'./src/views/my/my.js',
		'pages/supplybuy':'./src/views/supplybuy/supplybuy.js',
		'pages/find':'./src/views/find/find.js'
	},
	output: {
	    path: path.join(__dirname, '../../../../static/mypc'),
	    // 文件地址，使用绝对路径形式
	    filename: '[name].js',
	    chunkFilename: "[name].js",
	    //[name]这里是webpack提供的根据路口文件自动生成的名字
	    publicPath:'__MYPC__/' //本地''
	    //publicPath:'http://pic.myplas.com/mypc/' //测试
	    //publicPath:'http://statics.myplas.com/mypc/' //正式
	},
	plugins: [
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/index.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/index/index.html'),
			hash:true,
			inject:true,
			chunks:['pages/index']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/find.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/find/find.html'),
			hash:true,
			inject:true,
			chunks:['pages/find']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/supplybuy.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/supplybuy/supplybuy.html'),
			hash:true,
			inject:true,
			chunks:['pages/supplybuy']
		}),
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/mypczone/my.html'),
			template:path.join(__dirname, '../../../../www/view/default/mypczone/src/views/my/my.html'),
			hash:true,
			inject:true,
			chunks:['pages/my']
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
				loader: 'style!css'
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
		extensions: ['.js', '.vue'],
		// 别名，可以直接使用别名来代表设定的路径以及其他
		alias: {
			filter: path.join(__dirname, './src/filters'),
			components: path.join(__dirname, './src/components'),
			'vue': path.join(__dirname, './node_modules/vue/dist/vue.min.js'),
			'vue-router': path.join(__dirname, './node_modules/vue-router/dist/vue-router.min.js')
		}
	}
}