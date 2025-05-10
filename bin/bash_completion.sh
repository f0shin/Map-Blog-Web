#
# CakePHP 콘솔을 위한 Bash 자동 완성 파일.
# 이 파일을 `/etc/bash_completion.d/` 디렉토리 아래 `cake`라는 이름으로 복사하세요.
# 자세한 설정 방법은 아래 링크에서 확인할 수 있습니다:
# https://book.cakephp.org/5/en/console-commands/completion.html#how-to-enable-bash-autocompletion-for-the-cakephp-console
#


_cake()
{
    local cur prev opts cake
    COMPREPLY=()
    cake="${COMP_WORDS[0]}"
    cur="${COMP_WORDS[COMP_CWORD]}"
    prev="${COMP_WORDS[COMP_CWORD-1]}"

    if [[ "$cur" == -* ]] ; then
        if [[ ${COMP_CWORD} = 1 ]] ; then
            opts=$(${cake} completion options)
        elif [[ ${COMP_CWORD} = 2 ]] ; then
            opts=$(${cake} completion options "${COMP_WORDS[1]}")
        else
            opts=$(${cake} completion options "${COMP_WORDS[1]}" "${COMP_WORDS[2]}")
        fi

        COMPREPLY=( $(compgen -W "${opts}" -- ${cur}) )
        return 0
    fi

    if [[ ${COMP_CWORD} = 1 ]] ; then
        opts=$(${cake} completion commands)
        COMPREPLY=( $(compgen -W "${opts}" -- ${cur}) )
        return 0
    fi

    if [[ ${COMP_CWORD} = 2 ]] ; then
        opts=$(${cake} completion subcommands $prev)
        COMPREPLY=( $(compgen -W "${opts}" -- ${cur}) )
        if [[ $COMPREPLY = "" ]] ; then
            _filedir
            return 0
        fi
        return 0
    fi

    return 0
}

complete -F _cake cake bin/cake
