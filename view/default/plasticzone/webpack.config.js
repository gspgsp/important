/* 引入操作路径模块和webpack */
var path = require('path');
var webpack = require('webpack');
var HtmlWebpackPlugin = require('html-webpack-plugin')

module.exports = {

//	entry: './src/main.js',
//	output: {
//		path: path.join(__dirname, '../../../../static/myapp/plastic'),
//		filename: 'test.js'
//	},
//	plugins: [
//		new HtmlWebpackPlugin({
//			filename: 'index.html',
//			template:'index.html',
//			inject:true
//		})
//	],

	/* 输入文件 */
	entry: './src/main.js',
	output: {
	    path: path.join(__dirname, '../../../../static/myapp/plastic2'),
	    // 文件地址，使用绝对路径形式
	    filename: '[name].js',
	    chunkFilename: "[name].js",
	    //[name]这里是webpack提供的根据路口文件自动生成的名字
	    publicPath:'http://www.online.com/myapp/plastic2/' //本地
	    //publicPath:'http://pic.myplas.com/myapp/plastic2/' //测试
	    //publicPath:'http://statics.myplas.com/myapp/plastic2/' //正式
	},
	plugins: [
		new HtmlWebpackPlugin({
			filename: path.join(__dirname, '../../../../www/view/default/plasticzone/index.html'),
			template:'index_dev.html',
			hash:true,
			inject:true
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