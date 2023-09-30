
#include <ncurses.h>
#include <stdio.h>
#include <stdlib.h>

int main()
{	int ch = 1000;
//	WINDOW win WINDOW *newwin(int nlines, int ncols, int begin_y, int begin_x);
	WINDOW *win;

	initscr();			/* Start curses mode 		*/
	raw();				/* Line buffering disabled	*/
	keypad(stdscr, TRUE);		/* We get F1, F2 etc..		*/
	noecho();			/* Don't echo() while we do getch */

	while (ch != 0) {
		if(ch == KEY_F(10)) {		/* Without keypad enabled this will */
			printw("F1 Key pressed");/*  not get to us either	*/
			endwin();
			return 0;
		}

		attron(A_BOLD);
		mvaddstr(0,0,("Start"));
		mvaddstr(0,20,getenv("LEDGER_BEGIN"));
		mvaddstr(1,0,("Slut"));
		mvaddstr(1,20,getenv("LEDGER_END"));
		char foobar[80];
		sprintf(foobar,"hejsa %u   ",ch);
		mvaddstr(3,10,foobar);
		attroff(A_BOLD);

	refresh();			/* Print it on to the real screen */
    	ch = getch();			/* Wait for user input */
	}
	endwin();			/* End curses mode		  */
	return 0;
}
