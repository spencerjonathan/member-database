# grep "../../...." tmp.txt | sed -E  's,(..)/(..)/(....),update #__md_member set date_elected = "\3-\2-\1" where date_elected = "\1/\2/\3";,g'
#grep "..\...\....." tmp.txt | sed -E  's,(..)\.(..)\.(....),update #__md_member set date_elected = "\3-\2-\1" where date_elected = "\1.\2.\3";,g'
#grep '..\...\...$' tmp.txt | sed -E  's,(..)\.(..)\.(..),update #__md_member set date_elected = "20\3-\2-\1" where date_elected = "\1.\2.\3";,g'
#grep '..\..\...$' tmp.txt | sed -E  's,(..)\.(.)\.(..),update #__md_member set date_elected = "20\3-0\2-\1" where date_elected = "\1.\2.\3";,g'
grep "../../..$" tmp.txt | sed -E  's,(..)/(..)/(..),update #__md_member set date_elected = "20\3-\2-\1" where date_elected = "\1/\2/\3";,g'
