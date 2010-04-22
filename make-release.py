import os
import sys
import re

release_dir = '/tmp/pwrelease'

print 'Removing old directory'
os.system('rm -rf /tmp/pwrelease')

print 'Fetching newest repository'
os.system('svn export https://phpwatch.svn.sourceforge.net/svnroot/phpwatch/version-2-dev/trunk ' + release_dir)

print 'Extracting version'
f = open(release_dir + '/config.php')
config = f.read()
f.close()
r = re.search('define\(\'PW2_VERSION\', \'([0-9\.]+)[^\']*\'\);', config)
version = r.group(1)
print '    Got Version:', version

filename = 'phpWatch-' + version.replace('.','')
print 'Creating ' + filename + '.tar.gz'
os.system('tar -cvzf ' + filename + '.tar.gz trunk')
print 'Creating ' + filename + '.zip'
os.system('zip -r ' + filename + '.zip trunk')

print '*** COMPLETE ***'
