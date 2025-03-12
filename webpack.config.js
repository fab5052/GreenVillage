const path = require('path');
const WebpackNotifierPlugin = require('webpack-notifier');

module.exports = {
  mode: 'development',
  entry: './assets/app.js',
  output: {
    path: path.resolve(__dirname, 'public/build'),
    filename: 'app.js',
  },
  module: {
    rules: [
      // Traitement des fichiers JS/JSX avec Babel
      {
        test: /\.jsx?$/,
        exclude: /node_modules/,
        use: 'babel-loader',
      },
      // Traitement des fichiers CSS (si nécessaire)
      {
        test: /\.css$/,
        use: ['style-loader', 'css-loader'],
      },
      // Traitement des images (optionnel)
      {
        test: /\.(png|jpe?g|gif|svg)$/i,
        type: 'asset/resource',
      },
    ],
  },
  resolve: {
    // Permet d'importer des fichiers sans avoir à préciser l'extension
    extensions: ['.js', '.jsx', '.json', 'jest'],
  },
  plugins: [
    // Affiche une notification à chaque compilation réussie ou avec erreurs
    new WebpackNotifierPlugin({
      title: 'Webpack Build',
      alwaysNotify: true,
    }),
  ],
  devServer: {
    // Serve les fichiers depuis le répertoire 'public'
    static: {
      directory: path.join(__dirname, 'public'),
    },
    compress: true,
    port: 9000, // Le port peut être modifié selon tes besoins
    hot: true,
  },
};
