# Build diagrams from GraphViz source

source=src
clutter=.

default:
	for file in devices; do \
		dot -Tps2 $(source)/$$file.dot -o $(clutter)/$$file.ps && \
		ps2pdf $(clutter)/$$file.ps $(clutter)/$$file.pdf; \
	done
	for file in software keys; do \
		neato -Tps2 $(source)/$$file.dot -o $(clutter)/$$file.ps && \
		ps2pdf $(clutter)/$$file.ps $(clutter)/$$file.pdf; \
	done
	rm -f $(clutter)/*.ps

clean:
	rm -f  $(clutter)/*.pdf $(clutter)/*.ps

