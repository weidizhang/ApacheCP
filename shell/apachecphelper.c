#include <stdlib.h>
#include <stdio.h>
#include <string.h>

/*
 * Part of ApacheCP
 * http://github.com/ebildude123
 */

int main (int argc, char *argv[])
{
	char cmd[600] = "", **p;
	
	if (argc > 1) {				
		strcat(cmd, argv[1]);
		for (p = &argv[2]; *p; p++) {
			strcat(cmd, " ");
			strcat(cmd, *p);
		}
		
		setuid(0);
		
		system(cmd);
	}
	else {
		printf("apachecphelper: missing command\n");
	}
	
	return 0;
}