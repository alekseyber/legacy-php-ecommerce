const HTMLWebpackPlugin = require("html-webpack-plugin");
const { EvalSourceMapDevToolPlugin } = require("webpack"); //  SourceMapDevToolPlugin     HotModuleReplacementPlugin, , //ProvidePlugin,
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const CopyWebpackPlugin = require("copy-webpack-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
//const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');
const path = require("path");

const isDev = process.env.NODE_ENV === "development";
const isProd = !isDev;

console.log("isDev", isDev, process.env.NODE_ENV);

const publicPath = "/";

const optimization = () => {
  const config = {
    splitChunks: {
      chunks: "all",
      maxSize: 200000,
    },
   // runtimeChunk: "single",
    minimizer: [
      new CssMinimizerPlugin({
        minimizerOptions: {
          preset: [
            "default",
            {
              discardComments: { removeAll: isProd },
            },
          ],
        },
      }),
    ],
    minimize: true,
  };

  // if (isProd) {
  //   config.minimizer = [
  //    new OptimizeCssAssetWebpackPlugin(),
  //     new TerserWebpackPlugin(),
  //   ];
  //   config.minimize = true;
  //   //  config.splitChunks.maxSize = 500000;
  // }

  return config;
};

const filename = (ext) => `[name].${ext}`;
//const filename = ext => isDev ? `[name].${ext}` : `[name].[contenthash].${ext}`;
//const chunkFilename = ext => isDev ? `[id].${ext}` : `[id].[contenthash].${ext}`;

const cssLoaders = (extra) => {
  const loaders = [
    // {
    //   loader: MiniCssExtractPlugin.loader,
    //   options: {
    //     hmr: isDev,
    //     reloadAll: true,
    //   },
    // },
    MiniCssExtractPlugin.loader,
    { loader: "css-loader", options: { sourceMap: isDev } },
  ];

  if (extra) {
    loaders.push({
      loader: extra,
      options: { sourceMap: isDev },
    });
  }

  return loaders;
};

// const babelOptions = preset => {
//     const opts = {
//         presets: [
//             '@babel/preset-env'
//         ],
//         plugins: [
//             '@babel/plugin-proposal-class-properties'
//         ]
//     }

//     if (preset) {
//         opts.presets.push(preset)
//     }

//     return opts
// }

// const jsLoaders = () => {
//     const loaders = [{
//         loader: 'babel-loader',
//         options: babelOptions()
//     }]

//     // if (isDev) {
//     //   loaders.push('eslint-loader')
//     // }

//     return loaders
// }

const plugins = () => {
  const base = [
    new HTMLWebpackPlugin({
      // inject: true,
      template: "./index.html",
      minify: {
        collapseWhitespace: false, //isProd
      },
      hash: isProd,
    }),
    // new HTMLWebpackPlugin({
    //   template: "./delivery.html",
    //   minify: {
    //     collapseWhitespace: false, //isProd
    //   },
    //   hash: isProd,
    // }),
    // new HTMLWebpackPlugin({
    //   template: "./product.html",
    //   minify: {
    //     collapseWhitespace: false, //isProd
    //   },
    //   hash: isProd,
    // }),
    // new HTMLWebpackPlugin({
    //   template: "./order.html",
    //   minify: {
    //     collapseWhitespace: false, //isProd
    //   },
    //   hash: isProd,
    // }),
    // new HTMLWebpackPlugin({
    //     // inject: true,
    //     filename: 'category.html',
    //     template: './category.html',
    //     minify: {
    //         collapseWhitespace: false //isProd
    //     }
    // }),
    // new HTMLWebpackPlugin({
    //     // inject: true,
    //     filename: 'product.html',
    //     template: './product.html',
    //     minify: {
    //         collapseWhitespace: false //isProd
    //     }
    // }),
    // new HTMLWebpackPlugin({
    //     // inject: true,
    //     filename: 'news.html',
    //     template: './news.html',
    //     minify: {
    //         collapseWhitespace: false //isProd
    //     }
    // }),
    new CleanWebpackPlugin(),
    new CopyWebpackPlugin({
      patterns: [
        {
          from: path.resolve(__dirname, "src", "favicon.png"),
          to: path.resolve(__dirname, "dist"),
        },
        {
          from: path.resolve(__dirname, "src", "apple-touch-icon.png"),
          to: path.resolve(__dirname, "dist"),
        },
        {
          from: path.resolve(__dirname, "src", "images"),
          to: path.resolve(__dirname, "dist", "images"),
        },
      ],
    }),
    new MiniCssExtractPlugin({
      filename: "assets/css/" + filename("css"),
      // chunkFilename: chunkFilename('css'),
    }),
    new EvalSourceMapDevToolPlugin({
      exclude: ["vendor.js"],
    }),
    // new ProvidePlugin({
    //   $: "jquery",
    //   jQuery: "jquery",
    // }),
  ];

  // if (isProd) {
  //     base.push(new BundleAnalyzerPlugin())
  // }

  // if (isDev) {
  //   //base.push(new SourceMapDevToolPlugin({}));
  //   // base.push(new HotModuleReplacementPlugin({}));
  // }

  return base;
};

module.exports = {
  context: path.resolve(__dirname, "src"),
  mode: "development",
  target: isDev ? "web" : "browserslist",
  entry: {
    // styles: {
    //   import: "./scss/index.scss",
    //   dependOn: "app",
    // },
    app: ["./scss/index.scss", "./js/bootstrap", "./js/app"],
  },
  // entry: {
  //   // jquery: 'jquery',
  //   // main: "./js/index.js",
  //   //style: "./scss/index.scss",
  //   style: {
  //     import: "./scss/index.scss",
  //     dependOn: ["app", "bootstrap"],
  //   },
  //   bootstrap: {
  //     import: "./js/bootstrap",
  //     dependOn: ["app", "style"],
  //   },
  //   app: {
  //     import: "./js/app",
  //     dependOn: ["bootstrap", "style"],
  //   },
  //   // app: "./js/app",
  // },
  output: {
    filename: "assets/js/" + filename("js"),
    path: path.resolve(__dirname, "./dist"),
    publicPath: publicPath,
  },
  resolve: {
    //extensions: ['.js', '.json', '.png'],
    alias: {
      "@": path.resolve(__dirname, "src/"),
      "@App": path.resolve(__dirname, "src/js/app/"),
      "@AppUtil": path.resolve(__dirname, "src/js/app/app-util/"),
      "@AppFetch": path.resolve(__dirname, "src/js/app/app-fetch/"),
      "@Constans": path.resolve(__dirname, "src/js/app/constans/"),
      "@Components": path.resolve(__dirname, "src/js/components/"),
      "@Dom": path.resolve(__dirname, "src/js/dom/"),
    },
  },
  optimization: optimization(),
  devServer: {
    contentBase: path.resolve(__dirname, "dist"),
    port: 4200,
    hot: isDev,
    proxy: {
      "/": {
        target: "http://demo-shop.example",
        changeOrigin: true,
      },
    },
    // open: true,
    // open: 'firefox'
    // compress: true,
  },
  devtool: false, // isProd ? false : "source-map", //inline-source-map
  plugins: plugins(),
  module: {
    rules: [
      {
        test: /\.css$/,
        use: cssLoaders(),
      },
      {
        test: /\.s[ac]ss$/,
        use: cssLoaders("sass-loader"),
      },
      {
        test: /\.less$/i,
        use: cssLoaders("less-loader"),
      },
      {
        test: /\.(png|jpg|svg|gif|webp)$/,
        exclude: /fonts/,
        type: "asset/resource",
        generator: {
          filename: "assets/images/[name][ext][query]",
        },
      },
      {
        test: /\.(ttf|otf|woff|woff2|eot|svg)$/,
        type: "asset/resource",
        generator: {
          filename: "assets/fonts/[name][ext][query]",
        },
      },
      // {
      //   test: /\.(png|jpg|svg|gif|webp)$/,
      //   exclude: /fonts/,
      //   use: [
      //     {
      //       loader: "file-loader",
      //       options: {
      //         name: "assets/images/[name].[ext]",
      //       },
      //     },
      //   ],
      // },
      // {
      //   test: /\.(ttf|otf|woff|woff2|eot|svg)$/,
      //   use: [
      //     {
      //       loader: "file-loader",
      //       options: {
      //         name: "assets/fonts/[name].[ext]",
      //         esModule: false,
      //       },
      //     },
      //   ],
      // },
      {
        test: /\.m?js$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: "babel-loader",
          options: {
            presets: [
              [
                "@babel/preset-env",
                {
                  modules: false,
                  useBuiltIns: "entry",
                  corejs: "3.10.0",
                },
              ],
              // "@babel/preset-typescript",
            ],
            plugins: [
              "@babel/plugin-transform-runtime",
              // "@babel/plugin-proposal-private-methods",
              // "@babel/plugin-proposal-private-property-in-object",
            ],
          },
        },
      },
      // {
      //   test: require.resolve("jquery"),
      //   loader: "expose-loader",
      //   options: {
      //     exposes: ["$", "jQuery"],
      //   },
      // },
    ],
  },
};
