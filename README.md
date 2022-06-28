# CourseReviewETH

If you wanna see the forum in action: https://n.ethz.ch/~lteufelbe/coursereview/

Code for forum to review courses at ETH
Needs to be used with own https://n.ethz.ch/~user/ Website to have switch aai authentication and get user info.

It works for all courses in VVZ, so therfore should work for all Departments.
It could get confusing with reviews of courses which is for one Department a Minor Course and for another a Minor Course.

While I think this should be neglible if you want to host it for your Deparment specifically and change stuff feel free to do so.
Or also for the time I am not at ETH anymore and can't host it myself:

Needs to be used with own https://n.ethz.ch/~user/ Website to have switch aai authentication and get user info.

The DB needs to be populated with all the courses, every new Semester this should also be updated.

ssh nethz@slab1.ethz.ch
*enter ldap pwd*
cd homepage
git clone https://github.com/EvilBabyDemon/CourseReviewETH
mv CourseReviewETH coursereview

These are the only commands to set everything up.
