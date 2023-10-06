
#include <ncurses.h>
#include <stdio.h>
#include <stdlib.h>
void exitc() {	endwin();exit(0);}
int main()
{	int ch = 1000;
//	WINDOW win WINDOW *newwin(int nlines, int ncols, int begin_y, int begin_x);
	WINDOW *win;
	WINDOW *menu = newwin(3,80,0,0);
	WINDOW *main = newwin(25,80,4,0);
	initscr();			/* Start curses mode 		*/
	raw();			/* Line buffering disabled	*/
wborder(menu, '-', '-', '-','-','-','-','-','-');
wborder(main, '-', '-', '-','-','-','-','-','-');
	keypad(stdscr, TRUE);		/* We get F1, F2 etc..		*/
	noecho();			/* Don't echo() while we do getch */

	while (ch != 0) {
		if (ch == 3) exitc();
		if(ch == KEY_F(10)) {		/* Without keypad enabled this will */
			printw("F1 Key pressed");/*  not get to us either	*/
			endwin();
			return 0;
		}

		attron(A_BOLD);
		mvwaddstr(main,0,0,("Start"));
		mvwaddstr(main,0,20,getenv("LEDGER_BEGIN"));
		mvwaddstr(main,1,0,("Slut"));
		mvwaddstr(main,1,20,getenv("LEDGER_END"));
		char foobar[80];
		sprintf(foobar,"hejsa %u   ",ch);
		mvwaddstr(main,3,10,foobar);
		attroff(A_BOLD);
		wrefresh(main);			/* Print it on to the real screen */
		wrefresh(menu);			/* Print it on to the real screen */
		ch = wgetch(main);			/* Wait for user input */
	}
	endwin();			/* End curses mode		  */
	return 0;
}
