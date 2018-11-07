# Building the Documentation

## Requirements

- Python
- [Sphinx](http://www.sphinx-doc.org/)
- [Read the Docs Sphinx Theme](https://github.com/rtfd/sphinx_rtd_theme)

## Building the HTML Documentation

To build the complete documentation run:

```
$ make html
```

Afterwards you will find the HTML files in `build/html`.

## Proofreading Automation

### Setup

```
$ pip install docutils-ast-writer
$ npm install
```

### Usage

```
$ ./node_modules/.bin/textlint src
```

