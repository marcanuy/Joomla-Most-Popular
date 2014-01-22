#!/bin/sh
VERSION=$(grep -oP '(?<=<version>).*?(?=</version>)' pkg_mostpopular.xml)
zip -r packages/mod_articles_mostpopular.zip packages/mod_articles_mostpopular/
zip -r packages/plg_content_mostpopular.zip packages/plg_content_mostpopular/
zip -r pkg_mostpopular-v$VERSION.zip *
