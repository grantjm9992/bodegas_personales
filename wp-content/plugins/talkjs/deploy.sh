#! /bin/bash
# A modification of Terence Eden's deploy script as found here: https://github.com/thenbrent/multisite-user-management/blob/master/deploy.sh
# The difference is that this script allows overwriting older versions (to update e.g. the readme without forcing a new version update trigger on users).
# Terence's script itself is a modification of Dean Clatworthy's deploy script as found here: https://github.com/deanc/wordpress-plugin-git-svn
# The difference is that this script lives in the plugin's git repo & doesn't require an existing SVN repo.

# main config
PLUGINSLUG="talkjs"
CURRENTDIR=`pwd`
MAINFILE="talkjs.php" # this should be the name of your main php file in the wordpress plugin

# git config
GITPATH="$CURRENTDIR/" # this file should be in the base of your git repository

# svn config
SVNPATH="/Users/mrcnkoba/work/talkjs/wordpress" # path to a temp SVN repo. No trailing slash required and don't add trunk.
SVNURL="https://plugins.svn.wordpress.org/talkjs/" # Remote SVN repo on wordpress.org, with trailing slash
SVNUSER="talkjs" # your svn username


# Let's begin...
echo ".........................................."
echo
echo "Preparing to deploy wordpress plugin"
echo
echo ".........................................."
echo

# Check if subversion is installed before getting all worked up
if ! which svn >/dev/null; then
	echo "You'll need to install subversion before proceeding. Exiting....";
	exit 1;
fi

# Check version in readme.txt is the same as plugin file after translating both to unix line breaks to work around grep's failure to identify mac line breaks
NEWVERSION1=0.1.15
echo "readme.txt version: $NEWVERSION1"
NEWVERSION2=`grep "^[[:space:]\*]*Version:" $GITPATH/$MAINFILE | awk -F' ' '{print $NF}'`
echo "$MAINFILE version: $NEWVERSION2"

echo "Versions match in readme.txt and $MAINFILE. Let's proceed..."

TAG=$NEWVERSION1
REVISION=0
while git show-ref --tags --quiet --verify -- "refs/tags/$TAG"
	do
		if  [ "$1" != "--same-version" ]
			then
				echo "Version $TAG already exists as git tag. Pass --same-version to allow. Exiting....";
				exit 1;
			else
				let REVISION++
                OLDTAG=$TAG
				TAG=$NEWVERSION1-rev-$REVISION
				echo "Version $OLDTAG already exists as git tag, using $TAG..";
		fi
done

cd $GITPATH
echo -e "Enter a commit message for this new version: \c"
read COMMITMSG
git commit -am "$COMMITMSG"

echo "Tagging new version in git"
git tag -a "$TAG" -m "Tagging version $TAG"

echo "Pushing latest commit to origin, with tags"
git push origin master
git push origin master --tags

echo
echo "Creating local copy of SVN repo ..."
svn co $SVNURL $SVNPATH

echo "Clearing svn repo so we can overwrite it"
svn rm $SVNPATH/trunk/*

echo "Exporting the HEAD of master from git to the trunk of SVN"
git checkout-index -a -f --prefix=$SVNPATH/trunk/

echo "Ignoring github specific files and deployment script"
svn propset svn:ignore "deploy.sh
README.md
.git
.gitignore" "$SVNPATH/trunk/"

echo "Changing directory to SVN and committing to trunk"
cd $SVNPATH/trunk/
# Add all new files that are not set to be ignored
# NOTE FROM EGBERT: this command sometimes did not work appropriately and i don't understand why. replaced it by the line below.
# svn status | grep -v "^.[ \t]*\..*" | grep "^?" | awk '{print $2}' | xargs svn add
svn add *

echo "Hello"
svn commit --username=$SVNUSER -m "$COMMITMSG"

cd $SVNPATH

if [ "$1" == "--same-version" ] && [ "$TAG" != "$NEWVERSION1" ]
	then
		echo "Deleting old SVN tag so we can overwrite it.."
		svn delete tags/$NEWVERSION1/
fi

echo "Creating new SVN tag & committing it.."
svn copy trunk/ tags/$NEWVERSION1/
cd $SVNPATH/tags/$NEWVERSION1
svn commit --username=$SVNUSER -m "Tagging version $NEWVERSION1"

echo "Removing temporary directory $SVNPATH"
# rm -fr $SVNPATH/

echo "*** FIN ***"
