# Translations

Each translation of the documentation is maintained in a separate 
repository:

* [English Documentation](https://github.com/sebastianbergmann/phpunit-documentation-english)
* [Spanish Documentation](https://github.com/sebastianbergmann/phpunit-documentation-spanish)
* [French Documentation](https://github.com/sebastianbergmann/phpunit-documentation-french)
* [Brazilian Portuguese Documentation](https://github.com/sebastianbergmann/phpunit-documentation-brazilian-portuguese)
* [Japanese Documentation](https://github.com/sebastianbergmann/phpunit-documentation-japanese)
* [Simplified Chinese Documentation](https://github.com/sebastianbergmann/phpunit-documentation-chinese)

## Adding a new translation

If you want to create a new translation, please open an issue in the issue
tracker of the English documentation, stating which language you would like to
translate. A repository will be created and added to the available translations.

Ideally, you would already have prepared a first version based on a fork or
a copy of the English documentation, which will then be imported into the 
official repository. 

# Building the Documentation

## Requirements

- Python
- [Sphinx](http://www.sphinx-doc.org/)
- [Read the Docs Sphinx Theme](https://github.com/rtfd/sphinx_rtd_theme)

### Debian/Ubuntu

`sudo apt install python-sphinx`

`pip install sphinx_rtd_theme`

## Building the HTML Documentation

To build the complete documentation run:

    make html

# Output

Afterwards you will find the HTML files in `build/html`.
