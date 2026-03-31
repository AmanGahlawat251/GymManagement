const path = require('path');
const { styles } = require('@ckeditor/ckeditor5-dev-utils');

module.exports = (config, context) => {
  return {
    mode: 'development',
    entry: {
      app: path.resolve(__dirname, './app.js'),
    },
    output: {
        path: path.resolve( __dirname/*, 'dist' */),
        filename: 'bundle.js'
    },

    // Set watch to true for dev purposes.
    watch: false,
    mode: 'none',
    module: {
      rules: [
        {
          test: /\.svg$/,
          use: [ 'raw-loader' ]
        },
        {
          test: /\.js$/,
          exclude: /node_modules/,
          use: ['babel-loader']
        },
        {
          test: /\.css$/,
          use: [
            {
              loader: 'style-loader',
              options: {
                injectType: 'singletonStyleTag',
                attributes: {
                  'data-cke': true
                }
              }
            },
            'css-loader',
            {
              loader: 'postcss-loader',
              options: {
                postcssOptions: styles.getPostCssConfig({
                  themeImporter: {
                    themePath: require.resolve( '@ckeditor/ckeditor5-theme-lark' )
                  },
                  minify: true
                })
              }
            }
          ]
        },
        {
          test: /\.html$/i,
          exclude: /node_modules/,
          loader: 'html-loader',
        },
      ],
    },
    // Useful for debugging.
    devtool: 'source-map',

    // By default webpack logs warnings if the bundle is bigger than 200kb.
    performance: { hints: false },
  };

}
