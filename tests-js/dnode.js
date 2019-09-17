var fs = require("fs"),
    dnode = require("dnode"),
    vm = require("vm"),
    twigSource = fs.readFileSync("./twig.dev.js", "UTF-8"),
    context = vm.createContext({ window: {} });

vm.runInContext(twigSource, context);

var server = dnode(function (remote, conn) {
    this.render = function (name, source, parameters, cb) {
        vm.runInContext(source, context);
        parameters = vm.runInContext("(function (){" + parameters + "}());", context);
        cb(context.window.Twig.render(context.twig.templates[name], parameters));
    };
    this.exit = function (cb) {
        cb();
        setTimeout(function () {
            process.exit(0);
        }, 100);
    };
});
server.listen(7070);
