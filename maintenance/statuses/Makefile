# Build diagrams from GraphViz source

source=src
clutter=.

default:
	for file in devices; do \
		dot -Tsvg $(source)/$$file.dot -o $(clutter)/$$file.svg; \
	done
	for file in software keys; do \
		neato -Tsvg $(source)/$$file.dot -o $(clutter)/$$file.svg; \
	done
	rm -f $(clutter)/*.ps

clean:
	rm -f  $(clutter)/*.pdf $(clutter)/*.ps

