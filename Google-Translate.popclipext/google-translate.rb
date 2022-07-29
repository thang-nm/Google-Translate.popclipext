require 'net/http'
require 'json'

text = ENV['POPCLIP_TEXT']
modifiers = ENV['POPCLIP_MODIFIER_FLAGS']
tl = ENV[modifiers == 1048576 ? 'POPCLIP_OPTION_TLC' : 'POPCLIP_OPTION_TL']
match = tl.match(/\((.*)\)/)
tl = match[1]

uri = URI('https://translate.googleapis.com/translate_a/single')
params = {
  :client => 'gtx',
  :sl => 'auto',
  :tl => tl,
  :dt => 't',
  :q => text.unicode_normalize
}
uri.query = URI.encode_www_form(params)
response = Net::HTTP.get(uri)
json = JSON.parse(response)
puts json[0].reduce('') { |str, line| str + line[0] }
