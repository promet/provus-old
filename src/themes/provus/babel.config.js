module.exports = (api) => {
  api.cache(true);

  const presets = [
    [
      '@babel/preset-env', {
        corejs: '2',
        useBuiltIns: 'usage',
      },
    ],
    ['minify', {
      builtIns: false,
    }],
  ];

  const comments = false;

  return { presets, comments };
};
