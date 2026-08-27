# PlacePress

Keep `cgb-scripts` updated by editing the version in `package.json` then running `npm install`.

Use `nvm` to manage the Node version for this project as needed (e.g. `nvm use 14`). Some dependencies are not compatible with some versions of Node.

@todo: migrate this build off `cgb-scripts` to `@wordpress/scripts`. `cgb-scripts` last shipped in 2019 and pins `node-sass` 4.14.1, which is the only reason this project is pinned to x64 Node 14 (see `.nvmrc`). Building on a current Node emits a bundle whose failed SCSS modules throw at runtime — it passes `node --check` but breaks block registration in the editor. Migrating swaps in dart-sass and lifts the Node constraint.

Two things to watch when doing it:

- `cgb-scripts` silently injects `src/common.scss` into every `.scss` file (via sass-loader's `data` option). `@wordpress/scripts` will not, so each `style.scss`/`editor.scss` needs an explicit `@use "../common" as *;`.
- `save()` output must stay byte-identical, or every existing post using these blocks is invalidated. Build both ways and diff the serialized HTML before committing.

# Create Guten Block

This project was bootstrapped with [Create Guten Block](https://github.com/ahmadawais/create-guten-block).

Below you will find some information on how to run scripts.

You can find the most recent version of this guide [here](https://github.com/ahmadawais/create-guten-block).

## 👉 `npm start`

- Use to compile and run the block in development mode.
- Watches for any changes and reports back any errors in your code.

## 👉 `npm run build`

- Use to build production code for your block inside `dist` folder.
- Runs once and reports back the gzip file sizes of the produced code.

## 👉 `npm run eject`

- Use to eject your plugin out of `create-guten-block`.
- Provides all the configurations so you can customize the project as you want.
- It's a one-way street, `eject` and you have to maintain everything yourself.
- You don't normally have to `eject` a project because by ejecting you lose the connection with `create-guten-block` and from there onwards you have to update and maintain all the dependencies on your own.
