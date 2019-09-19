'use strict'
const utils = require('./utils')
const webpack = require('webpack')
const config = require('../config')
const merge = require('webpack-merge')
const path = require('path')
const baseWebpackConfig = require('./webpack.base.conf')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const HtmlWebpackPlugin = require('html-webpack-plugin')
const FriendlyErrorsPlugin = require('friendly-errors-webpack-plugin')
const portfinder = require('portfinder')

const HOST = process.env.HOST
const PORT = process.env.PORT && Number(process.env.PORT)

const devWebpackConfig = merge(baseWebpackConfig, {
  module: {
    rules: utils.styleLoaders({ sourceMap: config.dev.cssSourceMap, usePostCSS: true })
  },
  // cheap-module-eval-source-map is faster for development
  devtool: config.dev.devtool,

  // these devServer options should be customized in /config/index.js
  devServer: {
    clientLogLevel: 'warning',
    historyApiFallback: {
      rewrites: [
        { from: /.*/, to: path.posix.join(config.dev.assetsPublicPath, 'index.html') },
      ],
    },
    hot: true,
    contentBase: false, // since we use CopyWebpackPlugin.
    compress: true,
    host: HOST || config.dev.host,
    port: PORT || config.dev.port,
    open: config.dev.autoOpenBrowser,
    overlay: config.dev.errorOverlay
      ? { warnings: false, errors: true }
      : false,
    publicPath: config.dev.assetsPublicPath,
    proxy: config.dev.proxyTable,
    quiet: true, // necessary for FriendlyErrorsPlugin
    watchOptions: {
      poll: config.dev.poll,
    }
  },
  plugins: [
    new webpack.DefinePlugin({
      'process.env': require('../config/dev.env')
    }),
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NamedModulesPlugin(), // HMR shows correct file names in console on update.
    new webpack.NoEmitOnErrorsPlugin(),
    // https://github.com/ampedandwired/html-webpack-plugin
    new HtmlWebpackPlugin({
      filename: 'index.html',
      template: 'index.html',
      inject: true,
      isProd: process.env.NODE_ENV === 'production'
    }),
    // copy custom static assets
    new CopyWebpackPlugin([
      {
        from: path.resolve(__dirname, '../static'),
        to: config.dev.assetsSubDirectory,
        ignore: ['.*']
      }
    ])
  ]
})

module.exports = new Promise((resolve, reject) => {
  portfinder.basePort = process.env.PORT || config.dev.port
  portfinder.getPort((err, port) => {
    if (err) {
      reject(err)
    } else {
      // publish the new Port, necessary for e2e tests
      process.env.PORT = port
      // add port to devServer config
      devWebpackConfig.devServer.port = port

      // Add FriendlyErrorsPlugin
      devWebpackConfig.plugins.push(new FriendlyErrorsPlugin({
        compilationSuccessInfo: {
          messages: [`Your application is running here: http://${devWebpackConfig.devServer.host}:${port}`],
        },
        onErrors: config.dev.notifyOnErrors
        ? utils.createNotifierCallback()
        : undefined
      }))

      resolve(devWebpackConfig)
    }
  })
})

const axios=require('axios');
var port = process.env.PORT || config.dev.port
const express = require('express');
const https = require('https');
var apiServer = express()
var bodyParser = require('body-parser')
apiServer.use(bodyParser.urlencoded({ extended: true }))
apiServer.use(bodyParser.json())
var apiRouter = express.Router()
var fs = require('fs')
apiRouter.route('/:apiName')
  .all(function (req, res) {
    const agent = new https.Agent({  
      rejectUnauthorized: false
    });
    var api = {
      update: "https://pocketapi.48.cn/user/api/v1/client/update/group_team_star",
      livelist: "https://pocketapi.48.cn/live/api/v1/live/getLiveList",
      openlivelist: "https://pocketapi.48.cn/live/api/v1/live/getOpenLiveList",
      liveone: "https://pocketapi.48.cn/live/api/v1/live/getLiveOne",
      openliveone: "https://pocketapi.48.cn/live/api/v1/live/getOpenLiveOne",
    
      roomid: "https://pocketapi.48.cn/im/api/v1/im/room/info/type/source",
      roomlio: "https://pocketapi.48.cn/im/api/v1/chatroom/msg/list/homeowner",
      roomlia: "https://pocketapi.48.cn/im/api/v1/chatroom/msg/list/all",
    
      login: "https://pocketapi.48.cn/user/api/v1/login/app/mobile",
      checkin: "https://pocketapi.48.cn/user/api/v1/checkin",
      userhome: "https://pocketapi.48.cn/user/api/v1/user/info/home"
    };
    // console.log('headers:',req.headers);
    function headers(o){
      if (o.token) {
        //加入token
        this.token = o.token;
      }
      if (o.appInfo) {
        //加入appInfo
        this.appInfo =o.appInfo;
      }
      if (o.appinfo) {
        //加入appInfo
        this.appInfo =o.appinfo;
      }
    }
    // console.log(req.headers, new headers(req.headers));
    axios({
      url: api[req.params.apiName],
      method: 'post',
      headers: new headers(req.headers),
      data: req.body,
      httpsAgent: agent
    }).then(response=>{
      res.send(response.data);
    }).catch(e=>{
      console.log(e);
    });

    // res.send('no such api name');
/*     fs.readFile('./db.json', 'utf8', function (err, data) {
      if (err) throw err
      var data = JSON.parse(data)
      if (data[req.params.apiName]) {
        res.json(data[req.params.apiName])
      }
      else {
        res.send('no such api name')
      }
    }) */
  })
apiServer.use('/api', apiRouter);
apiServer.listen(port+1, function (err) {
  if (err) {
    console.log(err)
    return
  }
  console.log('Listening at http://localhost:'+(port+1)+'\n');
})