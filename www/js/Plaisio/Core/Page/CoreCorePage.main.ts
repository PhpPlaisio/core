requirejs.config({
  baseUrl: '/js',
  paths: {
    'jquery': 'jquery/jquery',
    'jquery.cookie': 'js-cookie/js.cookie',
    'js-cookie': 'js-cookie/js.cookie'
  }
});

require(['Plaisio/Core/Page/CoreCorePage'],
  function (CorePageDecorator: any)
  {
    CorePageDecorator.init();
  });

// Plaisio\Console\TypeScript\Helper\MarkHelper::md5: 02bc7a95108cb773dd9c4721e9ebcc8a
