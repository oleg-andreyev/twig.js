const fs = require("fs"),
    vm = require("vm"),
    rpc = require('json-rpc2'),
    twigSource = fs.readFileSync("./twig.dev.js", "UTF-8"),
    context = vm.createContext({window: {}});

vm.runInContext(twigSource, context);

const server = rpc.Server.$create({
    'websocket': true, // is true by default
    'headers': { // allow custom headers is empty by default
        'Access-Control-Allow-Origin': '*'
    }
});

function render(args, opt, callback) {
    let [name, source, parameters] = args;

    vm.runInContext(source, context);
    parameters = vm.runInContext("(function (){" + parameters + "}());", context);

    let result = context.window.Twig.render(context.twig.templates[name], parameters);

    callback(null, result);
}

function exit(args, opt, callback) {
    callback();
    setTimeout(() => {
        process.exit(0);
    }, 100);
}

server.expose('render', render);
server.expose('exit', exit);

server.listen(7070);