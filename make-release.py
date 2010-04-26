import os
import sys
import re

release_dir = '/tmp/release'

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

print 'Cleaning .tex'
os.system('rm ' + release_dir + '/*/*.tex')
os.system('rm ' + release_dir + '/*/*/*.tex')

filename = 'phpWatch-' + version.replace('.','')
print 'Creating ' + filename + '.tar.gz'
os.system('cd ' + release_dir + ' && tar -cvzf ' + filename + '.tar.gz *')
os.system('mv ' + release_dir + '/' + filename + '.tar.gz .')
print 'Creating ' + filename + '.zip'
os.system('cd ' + release_dir + ' && zip -r ' + filename + '.zip *')
os.system('mv ' + release_dir + '/' + filename + '.zip .')

print '*** COMPLETE ***'
print 'Remember to bump version file'
